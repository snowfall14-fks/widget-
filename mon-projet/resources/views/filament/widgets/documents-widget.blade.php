<x-filament-widgets::widget>
    <x-filament::section>

        <div
           x-data="{
                 statut: '',
                 couleurStatut: '#4f46e5',

                 envoyerFichier(fichier) {
                 this.statut = 'Envoi de « ' + fichier.name + ' » en cours...';
                 this.couleurStatut = '#4f46e5';

                 let donnees = new FormData();
                 donnees.append('fichier', fichier);

                 fetch('{{ route('documents.upload') }}', {
                   method: 'POST',
                   headers: {
                       'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                           },
                   body: donnees,
                         })
              .then(reponse => reponse.json())
              .then(data => {
                this.statut = '« ' + data.nom + ' » ' + data.statut.toLowerCase() + ' ✓';
                this.couleurStatut = data.statut === 'Indexé' ? '#22c55e' : '#f97316';
                setTimeout(() => location.reload(), 1200);
                // On recharge la page après un court délai pour que le
                // tableau affiche le nouveau document — une solution
                // simple, pas la plus élégante, mais fiable
             })
             .catch(erreur => {
                this.statut = 'Erreur lors de l\'envoi';
                this.couleurStatut = '#ef4444';
                console.error(erreur);
            });
                 }
            }"
        >

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h2 style="font-size: 18px; font-weight: 600;">Entraînement de l'IA</h2>
                <a href="{{ \App\Filament\Pages\Documents::getUrl() }}" style="font-size: 14px; color: #6366f1;">Voir tous les documents →</a>
            </div>

            <input
                type="file"
                x-ref="fileInput"
                accept=".pdf,.docx,.xlsx,.txt"
                style="display: none;"
                x-on:change="envoyerFichier($event.target.files[0])"
            >

            <div
                style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 32px; text-align: center; margin-bottom: 16px; cursor: pointer;"
                x-on:click="$refs.fileInput.click()"
                x-on:dragover.prevent="$el.style.borderColor = '#4f46e5'"
                x-on:dragleave.prevent="$el.style.borderColor = '#d1d5db'"
                x-on:drop.prevent="$el.style.borderColor = '#d1d5db'; envoyerFichier($event.dataTransfer.files[0])"
            >
                <p style="font-weight: 500;">Glissez-déposez vos fichiers ici</p>
                <p style="font-size: 14px; color: #6b7280;">PDF, DOCX ou Excel (max 50 Mo)</p>

                <x-filament::button
                    type="button"
                    style="margin-top: 12px;"
                    x-on:click.stop="$refs.fileInput.click()"
                >
                    Importer des documents
                </x-filament::button>

                <p x-show="statut" x-text="statut" :style="'font-size: 13px; margin-top: 10px; color: ' + couleurStatut"></p>
            </div>

            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px 16px 8px 0;">Nom du document</th>
                        <th style="padding: 8px 16px 8px 0;">Type</th>
                        <th style="padding: 8px 16px 8px 0;">Statut</th>
                        <th style="padding: 8px 0;">Ajouté le</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 8px 16px 8px 0;">{{ $document['nom'] }}</td>
                            <td style="padding: 8px 16px 8px 0;">{{ $document['type'] }}</td>
                            <td style="padding: 8px 16px 8px 0;">
                                <span style="padding: 4px 8px; border-radius: 9999px; font-size: 12px; background: #dcfce7; color: #15803d;">
                                    {{ $document['statut'] }}
                                </span>
                            </td>
                            <td style="padding: 8px 0;">{{ $document['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>