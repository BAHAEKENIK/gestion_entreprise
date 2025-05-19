<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Mes Tâches Assignées') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-card-container {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
        html.dark .page-card-container {
             background-color: var(--card-bg-dark);
             color: var(--text-light);
        }

        .custom-form-select {
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light);
            color: var(--text-dark);
        }
        html.dark .custom-form-select {
            background-color: var(--card-bg-dark); /* Inputs can match card bg in dark mode */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3);
            outline: none;
        }
        /* Ensure --primary-rgb is defined in app.blade.php */

        /* Table styling (mostly Tailwind dark: variants) */
        .tasks-table thead {
            background-color: var(--sidebar-bg-light); /* Light table header */
        }
        html.dark .tasks-table thead {
            background-color: var(--sidebar-bg-dark); /* Darker table header */
        }
        .tasks-table th {
            color: var(--text-muted-light);
        }
        html.dark .tasks-table th {
            color: var(--text-muted-dark);
        }
        .tasks-table tbody {
            background-color: var(--card-bg-light); /* Card background for table rows */
            divide-y: var(--border-color-light);
        }
        html.dark .tasks-table tbody {
            background-color: var(--card-bg-dark);
            divide-y: var(--border-color-dark);
        }
         .tasks-table td {
            color: var(--text-dark);
             border-bottom: 1px solid var(--border-color-light);
        }
        html.dark .tasks-table td {
            color: var(--text-light);
            border-bottom: 1px solid var(--border-color-dark);
        }
         .tasks-table td a {
            color: var(--primary-color);
        }
        html.dark .tasks-table td a {
            color: var(--secondary-color);
        }

        /* Status badge styling using Tailwind utility classes - these should be fine as they include dark: variants */
        /* Forcing text color on badges with vars IF NEEDED, but usually Tailwind handles this */
        .status-badge.terminee { background-color: #D1FAE5; color: #065F46; } html.dark .status-badge.terminee { background-color: #064E3B; color: #A7F3D0; }
        .status-badge.en-cours, .status-badge.en-revision { background-color: #FEF3C7; color: #92400E; } html.dark .status-badge.en-cours, html.dark .status-badge.en-revision { background-color: #78350F; color: #FDE68A; }
        .status-badge.annulee { background-color: #FEE2E2; color: #991B1B; } html.dark .status-badge.annulee { background-color: #7F1D1D; color: #FECACA; }
        .status-badge.a-faire { background-color: #DBEAFE; color: #1E40AF; } html.dark .status-badge.a-faire { background-color: #1E3A8A; color: #BFDBFE; }

         .action-link-update { color: #16A34A; } html.dark .action-link-update { color: #34D399; }
         .action-text-disabled { color: var(--text-muted-light); } html.dark .action-text-disabled { color: var(--text-muted-dark); }

    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-card-container bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8"> {{-- Use a consistent padding and let parent handle border --}}
                    @include('partials.flash-messages')

                     <form method="GET" action="{{ route('taches.employe.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                            <div>
                                <x-input-label for="statut_filter_employe" :value="__('Filtrer par Statut')" class="text-[var(--text-dark)]" />
                                <select name="statut_filter_employe" id="statut_filter_employe"
                                        class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les statuts</option>
                                    @foreach ($statuts as $statut)
                                        <option value="{{ $statut }}" {{ request('statut_filter_employe') == $statut ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div> {{-- This div ensures the button aligns well on larger screens with the select --}}
                                <x-primary-button class="mt-7 md:mt-0 h-10 text-sm self-start md:self-end">
                                    {{ __('Filtrer') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    @if($taches->count() > 0)
                        <div class="overflow-x-auto">
                             <table class="tasks-table min-w-full divide-y">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Titre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Assignée par</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date Fin Prévue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($taches as $tache)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('taches.show', $tache) }}" class="text-link">{{ Str::limit($tache->titre, 30) }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tache->assignePar->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                             <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full status-badge {{ Str::slug($tache->statut, '-') }}">
                                                {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tache->date_fin_prevue ? $tache->date_fin_prevue->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($tache->statut !== 'terminee' && $tache->statut !== 'annulee')
                                            <a href="{{ route('taches.realiser.form', $tache) }}" class="action-link-update hover:underline">Mettre à jour / Réaliser</a>
                                            @else
                                            <span class="action-text-disabled">Traitée</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $taches->appends(request()->query())->links() }}
                        </div>
                    @else
                        <p>Aucune tâche ne vous est actuellement assignée ou correspondant à vos filtres.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
