<x-filament-widgets::widget>
    <x-filament::section>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="font-size: 18px; font-weight: 600;">Performance de l'IA</h2>
            <a href="#" style="font-size: 14px; color: #6366f1;">Voir les détails →</a>
        </div>

        {{--
            TODO — RÈGLE MÉTIER À AFFINER (voir explication précédente
            sur les seuils de couleur arbitraires à valider avec l'équipe métier)
        --}}

        <div style="display: flex; gap: 16px; justify-content: space-around;">
            @foreach ($performances as $performance)
                @php
                    $couleur = match (true) {
                        $performance['valeur'] >= 85 => '#22c55e',
                        $performance['valeur'] >= 50 => '#3b82f6',
                        default => '#f97316',
                    };
                @endphp

                <div style="display: flex; flex-direction: column; align-items: center;">

                    <div style="position: relative; width: 80px; height: 80px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; background: conic-gradient({{ $couleur }} {{ $performance['valeur'] * 3.6 }}deg, #e5e7eb 0deg);">
                        <div style="position: absolute; width: 56px; height: 56px; background: white; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;">
                            {{ $performance['valeur'] }}%
                        </div>
                    </div>

                    <div style="font-size: 12px; color: #6b7280; text-align: center; margin-top: 8px;">
                        {{ $performance['label'] }}
                    </div>

                </div>
            @endforeach
        </div>

    </x-filament::section>
</x-filament-widgets::widget>