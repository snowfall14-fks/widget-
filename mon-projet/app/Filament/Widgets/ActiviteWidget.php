<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ActiviteWidget extends ChartWidget
{
    protected ?string $heading = 'Activité (Chat & Appel)';

    protected static ?int $sort = -5;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Chats',
                    'data' => [80, 95, 60, 110, 90, 70, 100, 85, 120, 95, 75, 90],
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Appels vocaux',
                    'data' => [40, 55, 30, 60, 45, 35, 50, 40, 65, 50, 38, 45],
                    'backgroundColor' => '#a855f7',
                ],
            ],
            'labels' => ['1 nov.', '3 nov.', '5 nov.', '8 nov.', '10 nov.', '13 nov.', '15 nov.', '18 nov.', '20 nov.', '23 nov.', '26 nov.', '30 nov.'],
        ];
    }
    // TODO — CONNEXION BASE DE DONNÉES :
    // Remplacer les tableaux 'data' par une vraie requête groupée par jour,
    // par exemple :
    //
    //     $chats = Conversation::where('canal', 'chat')
    //         ->whereBetween('created_at', [$debut, $fin])
    //         ->selectRaw('DATE(created_at) as jour, COUNT(*) as total')
    //         ->groupBy('jour')
    //         ->pluck('total');
    //
    // Et pareil pour 'labels', à partir des vraies dates de la période choisie.

    protected function getType(): string
    {
        return 'bar';
    }
}