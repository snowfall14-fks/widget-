<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|max:51200|mimes:pdf,docx,xlsx,txt',
            // max en Ko : 51200 = 50 Mo, comme annoncé sur ton dashboard
        ]);

        $fichier = $request->file('fichier');

        // 1. On enregistre tout de suite une trace en base, avec le
        // statut "En cours" — avant même de savoir si l'envoi à l'API
        // de ton collègue va réussir
        $document = Document::create([
            'nom_fichier' => $fichier->getClientOriginalName(),
            'type' => strtoupper($fichier->getClientOriginalExtension()),
            'statut' => 'En cours',
        ]);

        try {
            // 2. On transmet le fichier à l'API de ton collègue.
            // Http::attach() est la façon Laravel d'envoyer un vrai
            // fichier dans une requête, équivalent du FormData qu'on
            // utilisait côté JavaScript
            $reponse = Http::attach(
                'fichier',
                file_get_contents($fichier->getRealPath()),
                $fichier->getClientOriginalName()
            )->post('https://livekit.ibrahimsherif.cloud/indexer-document');

            $document->statut = $reponse->successful() ? 'Indexé' : 'Erreur';
            $document->save();

        } catch (\Exception $erreur) {
            Log::error('Erreur transmission document : ' . $erreur->getMessage());
            $document->statut = 'Erreur';
            $document->save();
        }

        return response()->json([
            'nom' => $document->nom_fichier,
            'type' => $document->type,
            'statut' => $document->statut,
            'date' => $document->created_at->format('d M Y'),
        ]);
    }
    public function destroy(\App\Models\Document $document)
 {
    $document->delete();
    return response()->json(['message' => 'Document supprimé']);
 }
}