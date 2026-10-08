<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Documents extends Page
{
    protected string $view = 'filament.pages.documents';



        public array $documents = [];

    public function mount(): void
    {
         $this->documents = \App\Models\Document::latest()->get()
             ->map(fn ($doc) => [
                'id' => $doc->id,
                 'nom' => $doc->nom_fichier,
                'type' => $doc->type,
             'statut' => $doc->statut,
             'date' => $doc->created_at->format('d M Y'),
             ])
     ->toArray();
    }
    // TODO — CONNEXION BASE DE DONNÉES :
    // Même logique que DocumentsWidget.php — remplacer par une méthode
    // mount() qui va chercher TOUS les documents en base (sans limite
    // de 4, contrairement au widget du dashboard qui n'en montre qu'un
    // aperçu). Voir DocumentsWidget.php pour l'exemple de requête.
}