<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Suivi des Pointages des Employés') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-card-container { background-color: var(--card-bg-light); color: var(--text-dark); }
        html.dark .page-card-container { background-color: var(--card-bg-dark); color: var(--text-light); }

        .page-section-title { font-size: 1.125rem; font-weight: 500; margin-bottom: 1rem; color: var(--text-dark); }
        html.dark .page-section-title { color: var(--text-light); }

        .custom-form-element, .custom-form-select { /* Reusing from roles create */
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light);
            color: var(--text-dark);
        }
        html.dark .custom-form-element, html.dark .custom-form-select {
            background-color: var(--card-bg-dark);
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-element:focus, .custom-form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3);
            outline: none;
        }
        .pointages-table thead { background-color: var(--sidebar-bg-light); }
        html.dark .pointages-table thead { background-color: var(--sidebar-bg-dark); }
        .pointages-table th { color: var(--text-muted-light); }
        html.dark .pointages-table th { color: var(--text-muted-dark); }
        .pointages-table tbody { background-color: var(--card-bg-light); }
        html.dark .pointages-table tbody { background-color: var(--card-bg-dark); }
        .pointages-table td { border-bottom: 1px solid var(--border-color-light); color: var(--text-dark); }
        html.dark .pointages-table td { border-bottom-color: var(--border-color-dark); color: var(--text-light); }
        .text-link-themed { color: var(--primary-color); }
        html.dark .text-link-themed { color: var(--secondary-color); }

        /* Alert styling */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-card-container bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    <h3 class="page-section-title">Filtrer les Pointages</h3>
                    <form method="GET" action="{{ route('pointages.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="date" :value="__('Date')" class="text-[var(--text-dark)]"/>
                                {{-- x-text-input for date should already handle dark mode styling via Tailwind --}}
                                <x-text-input id="date" class="custom-form-element block mt-1 w-full" type="date" name="date" :value="$selectedDate" />
                            </div>
                            <div>
                                <x-input-label for="employe_id_filter" :value="__('Employé (Optionnel)')" class="text-[var(--text-dark)]"/>
                                <select name="employe_id_filter" id="employe_id_filter" class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les employés</option>
                                    @foreach ($employes as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employe_id_filter') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-primary-button class="mt-7 h-10 text-sm md:mt-0 self-start md:self-end">
                                    {{ __('Filtrer') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    <h3 class="page-section-title mt-8">Pointages du {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h3>
                     @if($pointages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="pointages-table min-w-full divide-y">
                                <thead {{-- class="bg-gray-50 dark:bg-gray-700" --}}>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Employé</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Arrivée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Départ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody {{-- class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" --}}>
                                    @foreach ($pointages as $pointage)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $pointage->employe->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pointage->pointe_debut->format('H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $pointage->pointe_fin ? $pointage->pointe_fin->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($pointage->pointe_fin)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Terminé</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200">En cours</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ Str::limit($pointage->description, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('pointages.historique.employe', $pointage->employe_id) }}" class="text-link-themed hover:underline">Voir Historique</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $pointages->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <p class="text-[var(--text-muted-light)]">Aucun pointage trouvé pour cette date ou ce filtre.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
