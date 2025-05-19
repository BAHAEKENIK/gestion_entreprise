<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Mon Pointage') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-card-container { background-color: var(--card-bg-light); color: var(--text-dark); }
        html.dark .page-card-container { background-color: var(--card-bg-dark); color: var(--text-light); }

        .page-section-title { font-size: 1.125rem; font-weight: 500; margin-bottom: 1rem; color: var(--text-dark); }
        html.dark .page-section-title { color: var(--text-light); }

        .text-muted-themed { color: var(--text-muted-light); }
        html.dark .text-muted-themed { color: var(--text-muted-dark); }

        .custom-form-element { /* For description input */
            border: 1px solid var(--border-color-light); background-color: var(--content-bg-light); color: var(--text-dark);
        }
        html.dark .custom-form-element { background-color: var(--card-bg-dark); border-color: var(--border-color-dark); color: var(--text-light); }
        .custom-form-element:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); outline: none; }

        .pointages-table thead { background-color: var(--sidebar-bg-light); }
        html.dark .pointages-table thead { background-color: var(--sidebar-bg-dark); }
        .pointages-table th { color: var(--text-muted-light); }
        html.dark .pointages-table th { color: var(--text-muted-dark); }
        .pointages-table tbody { background-color: var(--card-bg-light); }
        html.dark .pointages-table tbody { background-color: var(--card-bg-dark); }
        .pointages-table td { border-bottom: 1px solid var(--border-color-light); color: var(--text-dark); }
        html.dark .pointages-table td { border-bottom-color: var(--border-color-dark); color: var(--text-light); }
        /* Alert styling */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
        .alert-info-custom { background-color: #DBEAFE; border-color: #BFDBFE; color: #1E40AF; } /* Blue */
        html.dark .alert-info-custom { background-color: #1E3A8A; border-color: #60A5FA; color: #BFDBFE; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-card-container bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    <h3 class="page-section-title">Pointage du Jour</h3>

                    @if ($pointageActif)
                        <p class="mb-2 text-[var(--text-dark)]">Vous avez pointé votre arrivée à : <span class="font-semibold">{{ $pointageActif->pointe_debut->format('H:i:s') }}</span> le {{ $pointageActif->pointe_debut->format('d/m/Y') }}.</p>
                        @if($pointageActif->description)
                        <p class="text-sm text-muted-themed mb-2">Description arrivée: {{ $pointageActif->description }}</p>
                        @endif
                        <form method="POST" action="{{ route('pointages.depart') }}">
                            @csrf
                            @method('PATCH')
                             <div class="mt-4">
                                <x-input-label for="description_depart" :value="__('Description pour le départ (optionnel)')" class="text-[var(--text-dark)]" />
                                <x-text-input id="description_depart" class="custom-form-element block mt-1 w-full" type="text" name="description_depart" />
                            </div>
                            <x-primary-button class="mt-4">
                                {{ __('Pointer Départ') }}
                            </x-primary-button>
                        </form>
                    @else
                        @php
                            $pointageCompletAujourdhui = \App\Models\Pointage::where('employe_id', Auth::id())
                                ->whereDate('pointe_debut', \Carbon\Carbon::today())
                                ->whereNotNull('pointe_fin')
                                ->exists();
                        @endphp
                        @if($pointageCompletAujourdhui)
                             <div class="mb-4 p-3 alert-container alert-info-custom rounded-md">Vous avez déjà complété votre pointage pour aujourd'hui.</div>
                        @else
                            <p class="mb-2 text-[var(--text-dark)]">Vous n'avez pas encore pointé votre arrivée aujourd'hui.</p>
                            <form method="POST" action="{{ route('pointages.arrivee') }}">
                                @csrf
                                <div class="mt-4">
                                    <x-input-label for="description_arrivee" :value="__('Description pour l\'arrivée (optionnel)')" class="text-[var(--text-dark)]" />
                                    <x-text-input id="description_arrivee" class="custom-form-element block mt-1 w-full" type="text" name="description_arrivee" />
                                </div>
                                <x-primary-button class="mt-4">
                                    {{ __('Pointer Arrivée') }}
                                </x-primary-button>
                            </form>
                        @endif
                    @endif

                    <hr class="my-6 border-[var(--border-color-light)]">

                    <h3 class="page-section-title">Historique de mes Pointages</h3>
                    @if($historiquePointages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="pointages-table min-w-full divide-y">
                                <thead {{-- class="bg-gray-50 dark:bg-gray-700" --}}>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Arrivée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Départ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody {{-- class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" --}}>
                                    @foreach ($historiquePointages as $pointage)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $pointage->pointe_debut->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pointage->pointe_debut->format('H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $pointage->pointe_fin ? $pointage->pointe_fin->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ Str::limit($pointage->description, 50) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $historiquePointages->links() }}
                        </div>
                    @else
                        <p class="text-muted-themed">Aucun historique de pointage trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
