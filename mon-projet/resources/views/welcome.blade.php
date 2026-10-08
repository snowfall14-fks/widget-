<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'AssistIA') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">

    <header class="w-full px-6 py-5 flex items-center justify-between max-w-6xl mx-auto">
        <div class="flex items-center gap-2 font-semibold text-lg">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white">A</span>
            {{ config('', 'AssistIA') }}
        </div>
        <a href="/admin/login"
           class="text-sm font-medium px-4 py-2 rounded-lg border border-slate-700 hover:border-indigo-500 hover:text-indigo-400 transition">
            Connexion
        </a>
    </header>

    <main class="flex-1 flex items-center justify-center px-6">
        <div class="max-w-2xl text-center">
            <span class="inline-block text-xs font-medium tracking-wide uppercase text-indigo-400 bg-indigo-950 border border-indigo-800 rounded-full px-3 py-1 mb-6">
                Assistant IA pour votre site
            </span>

            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6">
                Un assistant intelligent,<br class="hidden sm:block"> intégré à votre site
            </h1>

            <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto">
                {{ config('', 'AssistIA') }} répond à vos visiteurs par chat ou par appel vocal,
                et vous donne un tableau de bord complet pour suivre vos conversations et vos documents.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/admin/login"
                   class="w-full sm:w-auto px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-500 font-semibold transition">
                    Accéder au tableau de bord
                </a>
            </div>
        </div>
    </main>

    <footer class="text-center text-xs text-slate-600 py-6">
        &copy; {{ date('Y') }} {{ config('', 'AssistIA') }}. Tous droits réservés.
    </footer>

</body>
</html>