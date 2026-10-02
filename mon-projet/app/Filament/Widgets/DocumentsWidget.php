<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
// use App\Models\Document;
// AJOUTER CET IMPORT une fois le modèle Document créé (via php artisan make:model Document)

class DocumentsWidget extends Widget
{
    protected string $view = 'filament.widgets.documents-widget';

    protected static ?int $sort = -8;

    protected int | string | array $columnSpan = 1;

   public array $documents = [];

    public function mount(): void
    {
     $this->documents = \App\Models\Document::latest()->take(4)->get()
        ->map(fn ($doc) => [
            'nom' => $doc->nom_fichier,
            'type' => $doc->type,
            'statut' => $doc->statut,
            'date' => $doc->created_at->format('d M Y'),
        ])
        ->toArray();
    }
    // TODO — CONNEXION BASE DE DONNÉES :
    // Cette liste en dur sera supprimée. À la place, ajouter une méthode
    // mount() juste en dessous, qui va chercher les vrais documents :
    //
    //     public array $documents = [];
    //
    //     public function mount(): void
    //     {
    //         $this->documents = Document::where('entreprise_id', auth()->user()->entreprise_id)
    //             ->orderByDesc('created_at')
    //             ->get()
    //             ->map(fn ($doc) => [
    //                 'nom' => $doc->nom_fichier,
    //                 'type' => $doc->type,
    //                 'statut' => $doc->statut,
    //                 'date' => $doc->created_at->format('d M. Y'),
    //             ])
    //             ->toArray();
    //     }
    //
    // Le fichier documents-widget.blade.php, lui, NE CHANGE PAS —
    // il continuera de lire $documents exactement pareil, peu importe
    // d'où viennent les données.
}