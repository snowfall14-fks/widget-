<x-filament-panels::page>

    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 8px 16px 8px 0;">Date &amp; heure</th>
                    <th style="padding: 8px 16px 8px 0;">Canal</th>
                    <th style="padding: 8px 16px 8px 0;">Durée</th>
                    <th style="padding: 8px 16px 8px 0;">Messages</th>
                    <th style="padding: 8px 16px 8px 0;">Score</th>
                    <th style="padding: 8px 0;">Résumé</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conversations as $conversation)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 8px 16px 8px 0;">{{ $conversation['date'] }}</td>
                        <td style="padding: 8px 16px 8px 0;">{{ $conversation['canal'] }}</td>
                        <td style="padding: 8px 16px 8px 0;">{{ $conversation['duree'] }}</td>
                        <td style="padding: 8px 16px 8px 0;">{{ $conversation['messages'] }}</td>
                        <td style="padding: 8px 16px 8px 0;">{{ $conversation['score'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['resume'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</x-filament-panels::page>