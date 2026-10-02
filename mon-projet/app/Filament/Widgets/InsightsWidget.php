<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InsightsWidget extends Widget
{
    protected string $view = 'filament.widgets.insights-widget';

    protected static ?int $sort = -6;

    public array $questionsFrequentes = [
        'Quels sont vos tarifs ?',
        "Comment fonctionne l'abonnement ?",
        "Proposez-vous une période d'essai ?",
    ];

    public array $manquesDetectes = [
        'Informations sur la livraison',
        'Politique de retour',
        "Compatibilité avec d'autres outils",
    ];
    // TODO — CONNEXION BASE DE DONNÉES :
    // Remplacer ces deux listes par une méthode mount() qui va chercher :
    // - les questions les plus fréquentes, en regroupant les messages
    //   des conversations enregistrées et en comptant leurs occurrences
    // - les "manques", probablement en analysant les conversations où
    //   l'IA a déclenché une redirection humaine (voir le TODO du
    //   PerformanceWidget), pour en extraire les sujets récurrents
}