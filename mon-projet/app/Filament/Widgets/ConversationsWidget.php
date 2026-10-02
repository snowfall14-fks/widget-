<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ConversationsWidget extends Widget
{
    protected string $view = 'filament.widgets.conversations-widget';

    protected static ?int $sort = -4;

    protected int | string | array $columnSpan = 1;

    public array $conversations = [];

    public function mount(): void
    {
         $this->conversations = \App\Models\Conversation::latest()->take(4)->get()
             ->map(fn ($conv) => [
                 'date' => $conv->created_at->format('d M. H:i'),
                 'canal' => $conv->canal,
                 'duree' => $conv->duree_secondes ? gmdate('i:s', $conv->duree_secondes) : '—',
                 'messages' => $conv->nombre_messages ?? '—',
                 'score' => $conv->score ?? '—',
                 'resume' => $conv->resume ?? '—',
                     ])
             ->toArray();
    }
    // TODO — CONNEXION BASE DE DONNÉES :
    // Remplacer cette liste par une méthode mount() qui va chercher les
    // dernières conversations en base, par exemple :
    //
    //     public array $conversations = [];
    //
    //     public function mount(): void
    //     {
    //         $this->conversations = Conversation::where('entreprise_id', auth()->user()->entreprise_id)
    //             ->orderByDesc('created_at')
    //             ->limit(4)
    //             ->get()
    //             ->map(fn ($conv) => [
    //                 'date' => $conv->created_at->format('d M. H:i'),
    //                 'canal' => $conv->canal,
    //                 'duree' => $conv->duree_formattee,
    //                 'messages' => $conv->nombre_messages,
    //                 'score' => $conv->score,
    //                 'resume' => $conv->resume,
    //             ])
    //             ->toArray();
    //     }
    //
    // NOTE : ce widget pourrait aussi être reconstruit en vrai widget
    // "Table" Filament à ce moment-là (tri/recherche automatiques),
    // plutôt que cette table HTML manuelle.
}