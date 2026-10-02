<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PerformanceWidget extends Widget
{
    protected string $view = 'filament.widgets.performance-widget';
    
    protected static ?int $sort = -7;

    protected int | string | array $columnSpan = 1;

    public array $performances = [
        ['label' => 'Précision des réponses', 'valeur' => 92],
        ['label' => 'Taux de résolution RAG', 'valeur' => 87],
        ['label' => 'Redirection vers un humain', 'valeur' => 8],
    ];
    // TODO — CONNEXION BASE DE DONNÉES :
    // Remplacer cette liste par une méthode mount() qui calcule les
    // vrais pourcentages à partir des conversations enregistrées, par exemple :
    //
    //     public array $performances = [];
    //
    //     public function mount(): void
    //     {
    //         $this->performances = [
    //             ['label' => 'Précision des réponses', 'valeur' => Conversation::where(...)->avg('score_precision')],
    //             ['label' => 'Taux de résolution RAG', 'valeur' => Conversation::where(...)->where('resolu_par_rag', true)->count() / Conversation::where(...)->count() * 100],
    //             ['label' => 'Redirection vers un humain', 'valeur' => Conversation::where(...)->where('redirige_humain', true)->count() / Conversation::where(...)->count() * 100],
    //         ];
    //     }
}