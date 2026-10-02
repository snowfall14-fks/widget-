<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class EnTeteWidget extends Widget
{
    protected string $view = 'filament.widgets.en-tete-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -10;
    // Un nombre très bas force ce widget à s'afficher tout en haut,
    // avant tous les autres — plus le nombre est petit, plus tôt il apparaît

    public array $entreprise = [
        'nom' => 'iClan Innovation',
        'plan' => 'Pro',
        'statut' => 'Actif',
        'jetons_utilises' => 482300,
        'jetons_total' => 500000,
    ];

    public array $utilisateur = [
        'nom' => 'moha',
        'role' => 'Administrateur',
    ];
    // TODO — CONNEXION BASE DE DONNÉES :
    // Remplacer par une méthode mount() qui récupère ces infos depuis
    // auth()->user() et son entreprise liée, plus le vrai calcul des
    // jetons consommés sur la période en cours.
}