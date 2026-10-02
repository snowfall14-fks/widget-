<x-filament-panels::page>

    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

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
                            <td style="padding: 8px 0;">
                             <button
                                type="button"
                                onclick="supprimerDocument({{ $document['id'] }}, this)"
                             style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 13px;"
                             >
                             Supprimer
                            </button>
                       </td>
                       <th style="padding: 8px 0;">Actions</th>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <script>
function supprimerDocument(id, bouton) {
    if (!confirm('Supprimer ce document ?')) return;

    fetch('/admin/documents/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
    })
    .then(reponse => {
        if (reponse.ok) {
            bouton.closest('tr').remove();
        }
    });
}
</script>

</x-filament-panels::page>