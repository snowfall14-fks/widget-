<x-filament-widgets::widget>
    <x-filament::section>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">

            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-weight: 500;">Entreprise</span>
                <span style="font-weight: 600;">{{ $entreprise['nom'] }}</span>
                <span style="padding: 4px 8px; border-radius: 9999px; font-size: 12px; background: #e0e7ff; color: #4338ca;">
                    Plan {{ $entreprise['plan'] }}
                </span>
                <span style="padding: 4px 8px; border-radius: 9999px; font-size: 12px; background: #dcfce7; color: #15803d;">
                    ● {{ $entreprise['statut'] }}
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 24px;">

                <div style="min-width: 192px;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                        Jetons restants
                        {{ number_format($entreprise['jetons_utilises'], 0, ',', ' ') }}
                        / {{ number_format($entreprise['jetons_total'], 0, ',', ' ') }}
                    </div>
                    <div style="width: 100%; background: #e5e7eb; border-radius: 9999px; height: 8px;">
                        <div style="background: #4f46e5; height: 8px; border-radius: 9999px; width: {{ round($entreprise['jetons_utilises'] / $entreprise['jetons_total'] * 100) }}%;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 36px; height: 36px; border-radius: 9999px; background: #1f2937; color: white; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600;">
                        {{ collect(explode(' ', $utilisateur['nom']))->map(fn ($mot) => $mot[0])->join('') }}
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 500;">{{ $utilisateur['nom'] }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $utilisateur['role'] }}</div>
                    </div>
                </div>

            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>