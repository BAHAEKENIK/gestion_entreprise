<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Gestion des Tâches') }}
            </h2>
            {{-- Breeze components usually handle dark mode well. Assuming x-primary-button and links adapt. --}}
            <a style="color: lightgreen" href="{{ route('taches.create') }}" class="inline-flex items-center px-4 py-2 bg-[var(--primary-color)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[var(--primary-hover-color)] focus:outline-none focus:border-[var(--primary-hover-color)] focus:ring ring-indigo-300 dark:focus:ring-indigo-700 disabled:opacity-25 transition ease-in-out duration-150">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                {{ __('Nouvelle Tâche') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-card-container { /* Main container for the page content */
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            /* shadow-sm and sm:rounded-lg are from Breeze and generally good */
        }
        /* html.dark .page-card-container will be handled by variables in app.blade.php */

        .custom-form-select {
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light); /* Consistent input background */
            color: var(--text-dark);
            /* Tailwind classes for padding, rounding, width are good */
        }
        html.dark .custom-form-select {
            background-color: var(--card-bg-dark); /* Slightly lighter than pure content-bg-dark */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3);
            outline: none;
        }

        /* Alert styling (reused) */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }

        /* Table Styling - Primarily using Tailwind, adding vars for overrides if needed */
        .tasks-table thead {
            background-color: var(--sidebar-bg-light); /* Lighter header for table */
        }
        html.dark .tasks-table thead {
            background-color: var(--sidebar-bg-dark); /* Darker header for table in dark */
        }
        .tasks-table th { color: var(--text-muted-light); }
        html.dark .tasks-table th { color: var(--text-muted-dark); }
        .tasks-table tbody { background-color: var(--card-bg-light); } /* Table rows on card bg */
        html.dark .tasks-table tbody { background-color: var(--card-bg-dark); }
        .tasks-table td { border-bottom: 1px solid var(--border-color-light); }
        html.dark .tasks-table td { border-bottom-color: var(--border-color-dark); }

        .text-link { color: var(--primary-color); }
        .text-link:hover { text-decoration: underline; }
        html.dark .text-link { color: var(--secondary-color); }

    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-card-container bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    <form method="GET" action="{{ route('taches.directeur.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="statut_filter" :value="__('Statut')" class="text-[var(--text-dark)]" />
                                <select name="statut_filter" id="statut_filter"
                                        class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les statuts</option>
                                    @foreach ($statuts as $statut) {{-- Ensure $statuts is passed --}}
                                        <option value="{{ $statut }}" {{ request('statut_filter') == $statut ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <x-input-label for="employe_id_filter" :value="__('Employé')" class="text-[var(--text-dark)]" />
                                <select name="employe_id_filter" id="employe_id_filter"
                                        class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les employés</option>
                                    @foreach ($employes as $emp) {{-- Ensure $employes is passed --}}
                                        <option value="{{ $emp->id }}" {{ request('employe_id_filter') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="submit" class="h-10 text-sm mt-1 md:mt-0 self-end">
                                {{ __('Filtrer') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if($taches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="tasks-table min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead {{-- class="bg-gray-50 dark:bg-gray-700" --}}> {{-- Use custom class if needed --}}
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Titre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Employé</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date Fin Prévue</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody {{-- class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" --}}>
                                    @foreach ($taches as $tache)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('taches.show', $tache) }}" class="text-link">{{ Str::limit($tache->titre, 30) }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tache->employeAssignee->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                             <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($tache->statut == 'terminee') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($tache->statut == 'en_cours' || $tache->statut == 'en_revision') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200
                                                @elseif($tache->statut == 'annulee') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tache->date_fin_prevue ? $tache->date_fin_prevue->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a style="color: lightgreen" href="{{ route('taches.directeur.edit', $tache) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Modifier</a>
                                            <form action="{{ route('taches.destroy', $tache) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Supprimer</button>
                                            </form>
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
                        <p>Aucune tâche trouvée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
