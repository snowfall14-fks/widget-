<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Conversations extends Page
{
    protected string $view = 'filament.pages.conversations';

   

    public array $conversations = [];

    public function mount(): void
    {
         $this->conversations = \App\Models\Conversation::latest()->get()
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
    // Même logique que ConversationsWidget.php — remplacer par une
    // méthode mount() qui va chercher TOUTES les conversations en base
    // (sans limite de 4, contrairement au widget du dashboard). Voir
    // ConversationsWidget.php pour l'exemple de requête.
}