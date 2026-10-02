<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -9;
    protected function getStats(): array
{
    return [
        Stat::make('Conversations', '1 248')
            ->description('+12% vs mois précédent')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('info'),

        Stat::make('Minutes vocales', '3 462')
            ->description('+28% vs mois précédent')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-phone')
            ->color('success'),

        Stat::make('Messages', '5 921')
            ->description('+18% vs mois précédent')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-envelope')
            ->color('warning'),

        Stat::make('Score moyen', '4,6 / 5')
            ->description('+0,3 vs mois précédent')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-star')
            ->color('danger'),
    ];
}
}
