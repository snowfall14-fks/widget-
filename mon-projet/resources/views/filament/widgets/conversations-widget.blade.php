<x-filament-widgets::widget>
    <x-filament::section>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 600;">Dernières conversations</h2>
            <a href="{{ \App\Filament\Pages\Conversations::getUrl() }}" style="font-size: 14px; color: #6366f1;">Voir toutes les conversations →</a>
        </div>

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
                        <td style="padding: 8px 0;">{{ $conversation['date'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['canal'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['duree'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['messages'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['score'] }}</td>
                        <td style="padding: 8px 0;">{{ $conversation['resume'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </x-filament::section>
</x-filament-widgets::widget>