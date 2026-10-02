<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationWebhookController extends Controller
{
    public function store(Request $request)
    {
        // ⚠️ À AJUSTER DEMAIN selon ce que son agent envoie réellement.
        // Ces noms de champs sont des suppositions raisonnables, pas
        // confirmées — à vérifier avec lui avant de considérer que
        // cette route fonctionne vraiment.
        $valide = $request->validate([
            'canal' => 'required|string',
            'duree_secondes' => 'nullable|integer',
            'nombre_messages' => 'nullable|integer',
            'score' => 'nullable|numeric',
            'resume' => 'nullable|string',
        ]);

        Conversation::create($valide);

        return response()->json(['message' => 'Conversation enregistrée']);
    }
}