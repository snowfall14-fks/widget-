/* ============================================================
   WIDGET ASSISTANT — VERSION EMBARQUABLE
   Chat + rendez-vous + LiveKit + indexation de documents
============================================================ */

(function () {
    "use strict";

    if (document.getElementById("cw-root")) {
        return;
    }

    /* ========================================================
       1. CONFIGURATION DES API
    ======================================================== */

    var currentScript = document.currentScript;

    var API_BASE_URL = "https://livekit.ibrahimsherif.cloud";
    var CHAT_START_URL = API_BASE_URL + "/chat/start";
    var CHAT_URL = API_BASE_URL + "/chat";
    var RENDEZVOUS_URL = API_BASE_URL + "/rendezvous";
    var TOKEN_URL = API_BASE_URL + "/token";
    var INDEXER_DOCUMENT_URL = API_BASE_URL + "/indexer-document";

    var LIVEKIT_URL =
        "wss://assistia-demo-a6xsqk4x.livekit.cloud";

    var AGENT_CHAT_URL = currentScript
        ? currentScript.getAttribute("data-agent-chat")
        : null;

    var livekitRoom = null;
    var livekitScriptPromise = null;
    var welcomeLoaded = false;

    /* ========================================================
       2. FONT AWESOME
    ======================================================== */

    if (!document.querySelector("link[data-cw-fontawesome]")) {
        var fontAwesomeLink = document.createElement("link");
        fontAwesomeLink.rel = "stylesheet";
        fontAwesomeLink.href =
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css";
        fontAwesomeLink.setAttribute(
            "data-cw-fontawesome",
            "true"
        );
        document.head.appendChild(fontAwesomeLink);
    }

    /* ========================================================
       3. STYLE DU WIDGET
    ======================================================== */

    var css = `
        .cw-widget {
            position: fixed;
            right: 25px;
            bottom: 25px;
            z-index: 999999;
            font-family: Arial, sans-serif;
        }

        .cw-widget, .cw-widget * {
            box-sizing: border-box;
        }

        #cw-mainButton {
            width: 65px;
            height: 65px;
            border: none;
            border-radius: 50%;
            background: #4f46e5;
            color: white;
            font-size: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            transition: 0.3s;
        }

        #cw-mainButton:hover {
            transform: scale(1.08);
            background: #4338ca;
        }

        #cw-menu {
            position: absolute;
            right: 0;
            bottom: 80px;
            width: 280px;
            background: white;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .cw-option {
            width: 100%;
            border: none;
            background: white;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            border-radius: 12px;
            text-align: left;
            font-family: inherit;
        }

        .cw-option:hover {
            background: #f3f4f6;
        }

        .cw-option-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .cw-chat-icon,
        .cw-call-icon {
            background: #10b981;
        }

        .cw-option-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .cw-option-text strong {
            font-size: 15px;
            color: #111827;
        }

        .cw-option-text small {
            color: #6b7280;
            font-size: 12px;
        }

        .cw-window {
            position: absolute;
            right: 0;
            bottom: 20px;
            width: 360px;
            height: 520px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
        }

        .cw-hidden {
            display: none !important;
        }

        .cw-header,
        .cw-header1 {
            height: 70px;
            background: #15803d;
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .cw-header1 {
            background: #4f46e5;
        }

        .cw-assistant {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cw-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cw-assistant-name {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .cw-assistant-name strong {
            font-size: 14px;
        }

        .cw-online {
            font-size: 11px;
            color: #d1fae5;
        }

        .cw-close {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            color: white;
            cursor: pointer;
            flex-shrink: 0;
        }

        #cw-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cw-message {
            max-width: 85%;
            padding: 11px 14px;
            border-radius: 14px;
            font-size: 14px;
            line-height: 1.4;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .cw-bot {
            align-self: flex-start;
            background: white;
            color: #111827;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }

        .cw-user {
            align-self: flex-end;
            background: #15803d;
            color: white;
        }

        .cw-time {
            display: block;
            margin-top: 5px;
            font-size: 10px;
            opacity: 0.65;
            text-align: right;
        }

        #cw-chatForm {
            padding: 12px;
            display: flex;
            gap: 8px;
            border-top: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        #cw-messageInput {
            flex: 1;
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 22px;
            padding: 0 15px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            min-width: 0;
        }

        #cw-sendButton {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 50%;
            background: #15803d;
            color: white;
            cursor: pointer;
            flex-shrink: 0;
        }

        #cw-sendButton:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .cw-typing {
            display: flex;
            gap: 4px;
            align-items: center;
            padding: 14px;
        }

        .cw-typing span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #9ca3af;
            animation: cw-bounce 1.2s infinite;
        }

        .cw-typing span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .cw-typing span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes cw-bounce {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.4;
            }
            30% {
                transform: translateY(-5px);
                opacity: 1;
            }
        }

        #cw-callWindow {
            background: #111827;
            color: white;
        }

        .cw-call-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .cw-call-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            margin-bottom: 25px;
            animation: cw-pulse 1.8s infinite;
        }

        @keyframes cw-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79,70,229,0.7);
            }
            70% {
                box-shadow: 0 0 0 30px rgba(79,70,229,0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(79,70,229,0);
            }
        }

        .cw-call-content h2 {
            font-size: 19px;
            margin-bottom: 8px;
        }

        #cw-callStatus {
            color: #9ca3af;
            margin-bottom: 10px;
            text-align: center;
            padding: 0 15px;
        }

        #cw-timer {
            font-size: 22px;
            font-weight: bold;
        }

        .cw-call-controls {
            height: 90px;
            background: #0b1120;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .cw-call-button {
            width: 52px;
            height: 52px;
            border: none;
            border-radius: 50%;
            background: #374151;
            color: white;
            cursor: pointer;
            font-size: 18px;
        }

        .cw-call-button.cw-active {
            background: white;
            color: #111827;
        }

        .cw-end {
            background: #ef4444;
        }

        @media (max-width: 500px) {
            .cw-widget {
                right: 15px;
                bottom: 15px;
            }

            .cw-window {
                width: calc(100vw - 30px);
                height: 70vh;
                height: min(70vh, 520px);
            }

            #cw-menu {
                width: calc(100vw - 30px);
            }
        }
    `;

    var styleTag = document.createElement("style");
    styleTag.id = "cw-styles";
    styleTag.textContent = css;
    document.head.appendChild(styleTag);

    /* ========================================================
       4. HTML DU WIDGET
    ======================================================== */

    var container = document.createElement("div");
    container.id = "cw-root";

    container.innerHTML = `
        <div class="cw-widget">

            <div id="cw-menu" class="cw-hidden">

                <button id="cw-chatOption" class="cw-option" type="button">
                    <div class="cw-option-icon cw-chat-icon">
                        <i class="fa-solid fa-comment"></i>
                    </div>
                    <div class="cw-option-text">
                        <strong>ASSISTANT IA</strong>
                        <small>Envoyer un message</small>
                    </div>
                </button>

                <button id="cw-callOption" class="cw-option" type="button">
                    <div class="cw-option-icon cw-call-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="cw-option-text">
                        <strong>ASSISTANT call</strong>
                        <small>Parler avec l'assistant</small>
                    </div>
                </button>

            </div>

            <div id="cw-chatWindow" class="cw-window cw-hidden">

                <div class="cw-header">
                    <div class="cw-assistant">
                        <div class="cw-avatar">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="cw-assistant-name">
                            <strong>Assistant virtuel</strong>
                            <span class="cw-online">&#9679; En ligne</span>
                        </div>
                    </div>

                    <button id="cw-closeChat" class="cw-close" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div id="cw-messages">
                    <div class="cw-message cw-bot">
                        Bonjour 👋
                        Comment puis-je vous aider ?
                        <span class="cw-time">Maintenant</span>
                    </div>
                </div>

                <form id="cw-chatForm">
                    <input
                        id="cw-messageInput"
                        type="text"
                        placeholder="Écrivez votre message..."
                        autocomplete="off"
                    >
                    <button id="cw-sendButton" type="submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>

            </div>

            <div id="cw-callWindow" class="cw-window cw-hidden">

                <div class="cw-header1">
                    <div class="cw-assistant">
                        <div class="cw-avatar">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="cw-assistant-name">
                            <strong>Assistant virtuel</strong>
                            <span>Appel vocal</span>
                        </div>
                    </div>

                    <button id="cw-closeCall" class="cw-close" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="cw-call-content">
                    <div class="cw-call-avatar">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <h2>Assistant virtuel</h2>
                    <p id="cw-callStatus">Connexion en attente...</p>
                    <div id="cw-timer">00:00</div>
                </div>

                <div class="cw-call-controls">
                    <button id="cw-muteButton" class="cw-call-button" type="button">
                        <i class="fa-solid fa-microphone"></i>
                    </button>
                    <button id="cw-endCall" class="cw-call-button cw-end" type="button">
                        <i class="fa-solid fa-phone-slash"></i>
                    </button>
                </div>

            </div>

            <button id="cw-mainButton" type="button">
                <i id="cw-mainIcon" class="fa-solid fa-comments"></i>
            </button>

        </div>
    `;

    document.body.appendChild(container);

    /* ========================================================
       5. RÉCUPÉRATION DES ÉLÉMENTS
    ======================================================== */

    var mainButton = container.querySelector("#cw-mainButton");
    var mainIcon = container.querySelector("#cw-mainIcon");
    var menu = container.querySelector("#cw-menu");
    var chatOption = container.querySelector("#cw-chatOption");
    var callOption = container.querySelector("#cw-callOption");
    var chatWindow = container.querySelector("#cw-chatWindow");
    var callWindow = container.querySelector("#cw-callWindow");
    var closeChat = container.querySelector("#cw-closeChat");
    var closeCall = container.querySelector("#cw-closeCall");
    var chatForm = container.querySelector("#cw-chatForm");
    var messageInput = container.querySelector("#cw-messageInput");
    var sendButton = container.querySelector("#cw-sendButton");
    var messages = container.querySelector("#cw-messages");
    var muteButton = container.querySelector("#cw-muteButton");
    var endCall = container.querySelector("#cw-endCall");
    var timerElement = container.querySelector("#cw-timer");
    var callStatus = container.querySelector("#cw-callStatus");

    /* ========================================================
       6. VARIABLES D'ÉTAT
    ======================================================== */

    var seconds = 0;
    var interval = null;
    var muted = false;
    var history = [];
    var isSending = false;

    // Cette variable conserve les informations du rendez-vous
    // pendant que l'utilisateur répond aux questions.
    var rendezVous = null;

    /* ========================================================
       7. OUVERTURE ET FERMETURE DU MENU
    ======================================================== */

    mainButton.addEventListener("click", function () {
        if (
            !chatWindow.classList.contains("cw-hidden") ||
            !callWindow.classList.contains("cw-hidden")
        ) {
            closeEverything();
            return;
        }

        menu.classList.toggle("cw-hidden");

        mainIcon.className = menu.classList.contains("cw-hidden")
            ? "fa-solid fa-comments"
            : "fa-solid fa-xmark";
    });

    chatOption.addEventListener("click", function () {
        menu.classList.add("cw-hidden");
        chatWindow.classList.remove("cw-hidden");
        mainIcon.className = "fa-solid fa-xmark";
        messageInput.focus();

        chargerMessageBienvenue();
    });

    callOption.addEventListener("click", function () {
        menu.classList.add("cw-hidden");
        callWindow.classList.remove("cw-hidden");
        mainIcon.className = "fa-solid fa-xmark";

        startTimer();
        connecterLiveKit();
    });

    closeChat.addEventListener("click", function () {
        chatWindow.classList.add("cw-hidden");
        resetButton();
    });

    closeCall.addEventListener("click", function () {
        stopTimer();
        deconnecterLiveKit();
        callWindow.classList.add("cw-hidden");
        resetButton();
    });

    /* ========================================================
       8. EXTRACTION DES RÉPONSES DE L'API
       --------------------------------------------------------
       L'API /chat renvoie notamment le champ "reponse".
    ======================================================== */

    function extraireTexte(data) {
        if (typeof data === "string") {
            return data;
        }

        if (!data || typeof data !== "object") {
            return null;
        }

        var candidats = [
            data.reply,
            data.reponse,
            data.response,
            data.message,
            data.answer,
            data.text,
            data.content,
            data.detail
        ];

        for (var i = 0; i < candidats.length; i++) {
            if (
                typeof candidats[i] === "string" &&
                candidats[i].trim() !== ""
            ) {
                return candidats[i];
            }
        }

        return null;
    }

    async function lireReponse(response) {
        var type = response.headers.get("content-type") || "";

        if (type.indexOf("application/json") !== -1) {
            return await response.json();
        }

        return await response.text();
    }

    /* ========================================================
       9. MESSAGE DE BIENVENUE — GET /chat/start
    ======================================================== */

    async function chargerMessageBienvenue() {
        if (welcomeLoaded) {
            return;
        }

        try {
            var response = await fetch(CHAT_START_URL, {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                }
            });

            if (!response.ok) {
                return;
            }

            var data = await lireReponse(response);
            var welcome = extraireTexte(data);

            if (welcome) {
                var firstBot = messages.querySelector(
                    ".cw-message.cw-bot"
                );

                if (firstBot) {
                    firstBot.textContent = welcome;

                    var time = document.createElement("span");
                    time.className = "cw-time";
                    time.textContent = "Maintenant";

                    firstBot.appendChild(time);
                }

                welcomeLoaded = true;
            }
        } catch (error) {
            console.warn("[Widget] /chat/start :", error);
        }
    }

    /* ========================================================
       10. CHAT NORMAL — POST /chat
    ======================================================== */

    async function connecterAgentChat(text) {
        var url = AGENT_CHAT_URL || CHAT_URL;

        var response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                message: text,
                history: history.slice(-20)
            })
        });

        if (!response.ok) {
            if (response.status === 429) {
                throw new Error(
                    "Trop de messages envoyés. Patiente un instant."
                );
            }

            throw new Error(
                "Le service de chat est momentanément indisponible."
            );
        }

        var data = await lireReponse(response);
        var reply = extraireTexte(data);

        if (!reply) {
            throw new Error(
                "Le format de réponse de /chat n'est pas reconnu."
            );
        }

        return reply;
    }

    /* ========================================================
       11. DÉTECTION D'UNE DEMANDE DE RENDEZ-VOUS
    ======================================================== */

    function demandeRendezVous(text) {
        var message = text
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        return (
            message.includes("rendez-vous") ||
            message.includes("rendez vous") ||
            message.includes("prendre rendez") ||
            message.includes("prendre un rdv") ||
            message.includes("prendre rdv") ||
            message.includes("rdv") ||
            message.includes("un rendez")
        );
    }

    function commencerRendezVous() {
        rendezVous = {
            nom: "",
            telephone: "",
            date_souhaitee: "",
            motif: "",
            etape: "nom"
        };

        return (
            "Bien sûr ! Pour commencer, quel est votre nom complet ?"
        );
    }

    /* ========================================================
       12. POST /rendezvous
       --------------------------------------------------------
       Format attendu par ton API :

       {
           "nom": "...",
           "telephone": "...",
           "date_souhaitee": "...",
           "motif": "..."
       }
    ======================================================== */

    async function prendreRendezvous(donnees) {
        var response = await fetch(RENDEZVOUS_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(donnees)
        });

        var data = await lireReponse(response);

        if (!response.ok) {
            throw new Error(
                extraireTexte(data) ||
                "Impossible d'envoyer la demande de rendez-vous."
            );
        }

        return data;
    }

    /* ========================================================
       13. COLLECTE DES INFORMATIONS DU RENDEZ-VOUS
    ======================================================== */

    async function traiterRendezVous(text) {
        if (!rendezVous) {
            return commencerRendezVous();
        }

        var valeur = text.trim();

        if (rendezVous.etape === "nom") {
            rendezVous.nom = valeur;
            rendezVous.etape = "telephone";

            return "Merci. Quel est votre numéro de téléphone ?";
        }

        if (rendezVous.etape === "telephone") {
            var chiffres = valeur.replace(/\D/g, "");

            if (chiffres.length < 6) {
                return (
                    "Le numéro semble incomplet. " +
                    "Veuillez saisir un numéro de téléphone valide."
                );
            }

            rendezVous.telephone = valeur;
            rendezVous.etape = "date_souhaitee";

            return (
                "Merci. Quelle date et quelle heure souhaitez-vous " +
                "pour le rendez-vous ?"
            );
        }

        if (rendezVous.etape === "date_souhaitee") {
            rendezVous.date_souhaitee = valeur;
            rendezVous.etape = "motif";

            return "Très bien. Quel est le motif du rendez-vous ?";
        }

        if (rendezVous.etape === "motif") {
            rendezVous.motif = valeur;

            try {
                var resultat = await prendreRendezvous({
                    nom: rendezVous.nom,
                    telephone: rendezVous.telephone,
                    date_souhaitee: rendezVous.date_souhaitee,
                    motif: rendezVous.motif
                });

                rendezVous = null;

                return extraireTexte(resultat) ||
                    "Votre demande de rendez-vous a été envoyée. " +
                    "Veuillez vérifier la confirmation du service.";
            } catch (error) {
                // On conserve les données pour permettre une nouvelle
                // tentative si le serveur rencontre une erreur.
                rendezVous.etape = "motif";
                throw error;
            }
        }

        rendezVous = null;

        return "Une erreur est survenue. Veuillez recommencer.";
    }

    /* ========================================================
       14. INDEXATION D'UN DOCUMENT
    ======================================================== */

    async function indexerDocument(fichier) {
        if (!(fichier instanceof File)) {
            throw new Error("Le fichier fourni est invalide.");
        }

        var formData = new FormData();
        formData.append("file", fichier);

        var response = await fetch(INDEXER_DOCUMENT_URL, {
            method: "POST",
            headers: {
                "Accept": "application/json"
            },
            body: formData
        });

        var data = await lireReponse(response);

        if (!response.ok) {
            throw new Error(
                extraireTexte(data) ||
                "Impossible d'indexer le document."
            );
        }

        return data;
    }

    /* ========================================================
       15. ENVOI DES MESSAGES
       --------------------------------------------------------
       Les demandes de rendez-vous sont traitées ici.
       Les autres messages sont envoyés à /chat.
    ======================================================== */

    chatForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        if (isSending) {
            return;
        }

        var text = messageInput.value.trim();

        if (text === "") {
            return;
        }

        addMessage(text, "user");

        history.push({
            role: "user",
            content: text
        });

        messageInput.value = "";
        setSending(true);

        try {
            var reply;

            // Une collecte de rendez-vous est déjà commencée.
            if (rendezVous) {
                reply = await traiterRendezVous(text);
            }

            // L'utilisateur commence une demande de rendez-vous.
            else if (demandeRendezVous(text)) {
                reply = commencerRendezVous();
            }

            // Tout autre message est envoyé à l'agent IA.
            else {
                reply = await connecterAgentChat(text);
            }

            addMessage(reply, "bot");

            history.push({
                role: "assistant",
                content: reply
            });

        } catch (error) {
            console.error("[Widget] Erreur :", error);

            addMessage(
                error.message || "Une erreur est survenue.",
                "bot"
            );
        } finally {
            setSending(false);
        }
    });

    /* ========================================================
       16. INDICATEUR DE CHARGEMENT
    ======================================================== */

    function setSending(state) {
        isSending = state;
        sendButton.disabled = state;

        if (state) {
            showTyping();
        } else {
            hideTyping();
        }
    }

    function showTyping() {
        if (container.querySelector("#cw-typingIndicator")) {
            return;
        }

        var el = document.createElement("div");
        el.className = "cw-message cw-bot cw-typing";
        el.id = "cw-typingIndicator";
        el.innerHTML =
            "<span></span><span></span><span></span>";

        messages.appendChild(el);
        messages.scrollTop = messages.scrollHeight;
    }

    function hideTyping() {
        var el = container.querySelector("#cw-typingIndicator");

        if (el) {
            el.remove();
        }
    }

    /* ========================================================
       17. AFFICHAGE D'UN MESSAGE
    ======================================================== */

    function addMessage(text, type) {
        var message = document.createElement("div");
        message.className = "cw-message cw-" + type;
        message.textContent = String(text || "");

        var time = document.createElement("span");
        time.className = "cw-time";

        var now = new Date();

        time.textContent =
            now.getHours().toString().padStart(2, "0") + ":" +
            now.getMinutes().toString().padStart(2, "0");

        message.appendChild(time);
        messages.appendChild(message);

        messages.scrollTop = messages.scrollHeight;
    }

    /* ========================================================
       18. LIVEKIT — CHARGEMENT
    ======================================================== */

    function chargerLiveKit() {
        if (window.LivekitClient) {
            return Promise.resolve();
        }

        if (livekitScriptPromise) {
            return livekitScriptPromise;
        }

        livekitScriptPromise = new Promise(function (resolve, reject) {
            var script = document.createElement("script");

            script.src =
                "https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.umd.min.js";

            script.onload = resolve;

            script.onerror = function () {
                livekitScriptPromise = null;
                reject(new Error("Impossible de charger LiveKit."));
            };

            document.head.appendChild(script);
        });

        return livekitScriptPromise;
    }

    /* ========================================================
       19. CONNEXION VOCALE LIVEKIT
    ======================================================== */

    async function connecterLiveKit() {
        try {
            callStatus.textContent = "Connexion à l'assistant...";

            await chargerLiveKit();

            var tokenResponse = await fetch(TOKEN_URL, {
                method: "POST",
                headers: {
                    "Accept": "application/json"
                }
            });

            if (!tokenResponse.ok) {
                throw new Error(
                    "Le serveur n'a pas pu générer le token vocal."
                );
            }

            var tokenData = await lireReponse(tokenResponse);

            var participantToken =
                tokenData.participant_token ||
                tokenData.token ||
                tokenData.access_token;

            var serverUrl =
                tokenData.server_url ||
                tokenData.url ||
                LIVEKIT_URL;

            if (!participantToken) {
                throw new Error(
                    "Le endpoint /token n'a pas renvoyé de token LiveKit."
                );
            }

            livekitRoom = new LivekitClient.Room();

            livekitRoom.on(
                LivekitClient.RoomEvent.TrackSubscribed,
                function (track) {
                    if (
                        track.kind ===
                        LivekitClient.Track.Kind.Audio
                    ) {
                        var audioElement = track.attach();
                        document.body.appendChild(audioElement);
                    }
                }
            );

            livekitRoom.on(
                LivekitClient.RoomEvent.Disconnected,
                function () {
                    callStatus.textContent = "Appel terminé";
                    stopTimer();
                }
            );

            await livekitRoom.connect(
                serverUrl,
                participantToken
            );

            await livekitRoom.localParticipant
                .setMicrophoneEnabled(!muted);

            callStatus.textContent =
                "Vous êtes connecté à l'assistant";

        } catch (error) {
            console.error("[Widget] LiveKit :", error);

            callStatus.textContent =
                error.message || "Connexion vocale impossible";

            stopTimer();
        }
    }

    async function deconnecterLiveKit() {
        if (!livekitRoom) {
            return;
        }

        try {
            await livekitRoom.localParticipant
                .setMicrophoneEnabled(false);

            await livekitRoom.disconnect();

        } catch (error) {
            console.warn(
                "[Widget] Erreur de fermeture LiveKit :",
                error
            );
        } finally {
            livekitRoom = null;
        }
    }

    /* ========================================================
       20. MINUTEUR D'APPEL
    ======================================================== */

    function startTimer() {
        stopTimer();

        seconds = 0;
        timerElement.textContent = "00:00";
        callStatus.textContent = "Connexion à l'assistant...";

        interval = setInterval(function () {
            seconds++;

            var minutes = Math.floor(seconds / 60)
                .toString()
                .padStart(2, "0");

            var secs = (seconds % 60)
                .toString()
                .padStart(2, "0");

            timerElement.textContent = minutes + ":" + secs;
        }, 1000);
    }

    function stopTimer() {
        if (interval !== null) {
            clearInterval(interval);
            interval = null;
        }
    }

    /* ========================================================
       21. MICRO
    ======================================================== */

    muteButton.addEventListener("click", function () {
        muted = !muted;

        muteButton.classList.toggle("cw-active", muted);

        muteButton.innerHTML = muted
            ? '<i class="fa-solid fa-microphone-slash"></i>'
            : '<i class="fa-solid fa-microphone"></i>';

        if (livekitRoom && livekitRoom.localParticipant) {
            livekitRoom.localParticipant
                .setMicrophoneEnabled(!muted)
                .catch(function (error) {
                    console.error(
                        "[Widget] Erreur micro :",
                        error
                    );
                });
        }
    });

    /* ========================================================
       22. RACCROCHER
    ======================================================== */

    endCall.addEventListener("click", function () {
        stopTimer();
        deconnecterLiveKit();

        callStatus.textContent = "Appel terminé";
        timerElement.textContent = "00:00";

        setTimeout(function () {
            callWindow.classList.add("cw-hidden");
            resetButton();
        }, 500);
    });

    /* ========================================================
       23. FERMETURE GÉNÉRALE
    ======================================================== */

    function closeEverything() {
        menu.classList.add("cw-hidden");
        chatWindow.classList.add("cw-hidden");

        if (!callWindow.classList.contains("cw-hidden")) {
            deconnecterLiveKit();
        }

        callWindow.classList.add("cw-hidden");

        stopTimer();
        resetButton();
    }

    function resetButton() {
        mainIcon.className = "fa-solid fa-comments";
    }

    /* ========================================================
       24. API PUBLIQUE DU WIDGET
    ======================================================== */

    window.CWWidgetAPI = {
        startChat: chargerMessageBienvenue,
        sendChat: connecterAgentChat,
        prendreRendezvous: prendreRendezvous,
        indexerDocument: indexerDocument,
        connectVoice: connecterLiveKit,
        disconnectVoice: deconnecterLiveKit
    };

})();