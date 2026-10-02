<x-filament-widgets::widget>
    <x-filament::section>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 600;">Insights &amp; Résumé intelligent</h2>
            <a href="#" style="font-size: 14px; color: #6366f1;">Voir l'analyse complète →</a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

            <div>
                <h3 style="font-weight: 500; margin-bottom: 8px;">Questions fréquentes</h3>
                <ol style="list-style: decimal; padding-left: 20px; font-size: 14px;">
                    @foreach ($questionsFrequentes as $question)
                        <li style="margin-bottom: 4px;">{{ $question }}</li>
                    @endforeach
                </ol>
            </div>

            <div>
                <h3 style="font-weight: 500; margin-bottom: 8px;">Manques détectés dans vos documents</h3>
                <ul style="list-style: disc; padding-left: 20px; font-size: 14px;">
                    @foreach ($manquesDetectes as $manque)
                        <li style="margin-bottom: 4px;">{{ $manque }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>