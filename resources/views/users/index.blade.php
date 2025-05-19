<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
            {{-- Text color should adapt based on Breeze's dark:text-gray-200 or your theme variables --}}
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Gestion des Utilisateurs (Employés)') }}
            </h2>
            <div class="flex space-x-2">
                {{-- Buttons are styled with Tailwind and should be theme-aware. Inline styles like "color: lightblue" will override. Remove them. --}}
                @can('user-create')
                    <a style="color: red" href="{{ route('users.import.form') }}"
                       class="inline-flex items-center px-4 py-2 bg-teal-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600 active:bg-teal-700 focus:outline-none focus:border-teal-700 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12L12 19" /> <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L9 15m3-3l3 3" />
                        </svg>
                        {{ __('Importer') }}
                    </a>
                @endcan
                @can('user-export')
                    <a style="color:lightgreen" href="{{ route('users.export') }}"
                       class="inline-flex items-center px-4 py-2 bg-sky-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-600 active:bg-sky-700 focus:outline-none focus:border-sky-700 focus:ring ring-sky-300 disabled:opacity-25 transition ease-in-out duration-150">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                       </svg>
                        {{ __('Exporter') }}
                    </a>
                @endcan
                @can('user-create')
                    <a style="color: lightcoral" href="{{ route('users.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Nouveau Utilisateur') }}
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Apply theme variables to the main card container if it's not a default Breeze styled one */
        .page-content-card {
            background-color: var(--card-bg-light);
            color: var(--text-dark); /* Default text color for content within the card */
        }
        /* html.dark .page-content-card will be handled by the :root html.dark variables in app.blade.php */

        /* Example for custom table styles using variables if Breeze table styles are overridden or insufficient */
        .custom-styled-table {
            min-width: 100%;
            border-collapse: collapse; /* Added for consistency */
        }
        .custom-styled-table thead {
            background-color: var(--sidebar-active-bg-light); /* Lightly themed header */
        }
        html.dark .custom-styled-table thead {
            background-color: var(--sidebar-active-bg-dark);
        }
        .custom-styled-table th {
            padding: 0.75rem 1.5rem; /* px-6 py-3 */
            text-align: left;
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
            color: var(--sidebar-text-light); /* Themed text for header */
            text-transform: uppercase;
            letter-spacing: 0.05em; /* tracking-wider */
        }
        html.dark .custom-styled-table th {
             color: var(--sidebar-text-dark);
        }

        .custom-styled-table tbody {
            background-color: var(--card-bg-light);
            divide-y: var(--border-color-light); /* For divide-gray-200 */
        }
        html.dark .custom-styled-table tbody {
            background-color: var(--card-bg-dark);
            divide-y: var(--border-color-dark); /* For dark:divide-gray-700 */
        }

        .custom-styled-table td {
            padding: 1rem 1.5rem; /* px-6 py-4 */
            white-space: nowrap;
            font-size: 0.875rem; /* text-sm */
            color: var(--text-dark);
             border-bottom: 1px solid var(--border-color-light);
        }
         html.dark .custom-styled-table td {
            color: var(--text-light);
            border-bottom: 1px solid var(--border-color-dark);
        }

        .custom-styled-table td .text-muted {
            color: var(--text-muted-light);
        }
        html.dark .custom-styled-table td .text-muted {
            color: var(--text-muted-dark);
        }

        /* Custom action button styles if needed */
        .action-btn {
            padding: 0.25rem 0.5rem; /* p-1 */
            border-radius: 0.25rem; /* rounded */
            transition: background-color 0.15s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem; /* text-sm */
        }
        .action-btn-view { color: var(--primary-color); }
        .action-btn-view:hover { background-color: rgba(var(--primary-rgb), 0.1); } /* Use primary-rgb */
        html.dark .action-btn-view { color: var(--secondary-color); } /* Lighter for dark mode */
        html.dark .action-btn-view:hover { background-color: rgba(var(--primary-rgb, 129, 140, 248), 0.15); } /* Dark mode hover primary */

        .action-btn-edit { color: #D97706; } /* amber-600 */
        .action-btn-edit:hover { background-color: #FEF3C7; } /* amber-100 */
        html.dark .action-btn-edit { color: #FBBF24; } /* amber-400 */
        html.dark .action-btn-edit:hover { background-color: #78350F; } /* amber-900 */

        .action-btn-delete { color: #DC2626; } /* red-600 */
        .action-btn-delete:hover { background-color: #FEE2E2; } /* red-100 */
        html.dark .action-btn-delete { color: #F87171; } /* red-400 */
        html.dark .action-btn-delete:hover { background-color: #7F1D1D; } /* red-900 */

        .action-btn-task { color: #059669; } /* green-600 */
        .action-btn-task:hover { background-color: #D1FAE5; } /* green-100 */
        html.dark .action-btn-task { color: #34D399; } /* green-400 */
        html.dark .action-btn-task:hover { background-color: #065F46; } /* green-800 */

    </style>
    @endpush

    <div class="py-12">
        {{-- Added custom class to this outer div to apply themed background --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-content-card overflow-hidden shadow-sm sm:rounded-lg"> {{-- Breeze classes + your theme var based bg --}}
                <div class="p-6"> {{-- Text color for inner content will also inherit from .page-content-card --}}

                    @include('partials.flash-messages')

                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                        {{-- Search form - uses Tailwind classes, should be theme-aware --}}
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                            <div class="flex">
                                <input
                                    type="text"
                                    name="search"
                                    class="block w-full sm:w-64 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400"
                                    placeholder="Rechercher..."
                                    value="{{ request('search') }}">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 dark:focus:ring-offset-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                        {{-- Per page select - uses Tailwind classes, should be theme-aware --}}
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                             <div class="flex items-center">
                                <label for="per_page" class="mr-2 text-sm text-gray-700 dark:text-gray-300">Afficher:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()"
                                        class="block w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200 text-sm">
                                    <option value="7" {{ request('per_page', 7) == 7 ? 'selected' : '' }}>7</option>
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            {{-- Applying custom table class if more specific theming needed beyond Tailwind defaults --}}
                            <table class="custom-styled-table min-w-full">
                                <thead {{-- class="bg-gray-50 dark:bg-gray-700" --}}>
                                    <tr>
                                        <th style="width:20%">Nom Complet</th>
                                        <th style="width:20%">Email</th>
                                        <th style="width: 20%">Rôles</th>
                                        <th style="width: 20%">Statut</th>
                                        <th style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody {{-- class="divide-y divide-gray-200 dark:divide-gray-700" --}}>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="font-medium">{{ $user->name }}</td>
                                            <td class="text-muted">{{ $user->email }}</td>
                                            <td class="text-muted">
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $v)
                                                        @if($v !== 'directeur')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $v === 'employe' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100' }}">
                                                                {{ Str::ucfirst($v) }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                                        Aucun rôle assigné
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($user->statut == 'actif') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($user->statut == 'inactif') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                                    {{ ucfirst($user->statut ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td class="user-actions space-x-1"> {{-- Using Tailwind space for consistency if desired --}}
                                                @can('user-list')
                                                <a href="{{ route('users.show', $user->id) }}" class="action-btn action-btn-view" title="Consulter">
                                                    <i class="fas fa-eye"></i><span class="hidden md:inline ml-1">Voir</span>
                                                </a>
                                                @endcan
                                                @can('user-edit')
                                                <a href="{{ route('users.edit', $user->id) }}" class="action-btn action-btn-edit" title="Modifier">
                                                   <i class="fas fa-pencil-alt"></i><span class="hidden md:inline ml-1">Modifier</span>
                                                </a>
                                                @endcan
                                                @if(Auth::user()->id != $user->id && !$user->hasRole('directeur')) {{-- Example condition --}}
                                                    @can('user-delete')
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" id="delete-form-{{ $user->id }}">
                                                        @csrf @method('DELETE')
                                                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')" class="action-btn action-btn-delete" title="Supprimer">
                                                           <i class="fas fa-trash-alt"></i><span class="hidden md:inline ml-1">Supprimer</span>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                @endif
                                                @if($user->hasRole('employe'))
                                                    <a href="{{ route('taches.create', ['user' => $user->id]) }}" class="action-btn action-btn-task" title="Affecter une tâche">
                                                        <i class="fas fa-tasks"></i><span class="hidden md:inline ml-1">Tâche</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <p>{{ __('Aucun utilisateur (employé) trouvé.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id, userName) {
            Swal.fire({
                title: 'Êtes-vous sûr(e) ?',
                html: `Voulez-vous vraiment supprimer l'utilisateur <strong>${userName}</strong> ? <br/>Cette action est irréversible.`,
                icon: 'warning',
                iconColor: '#f8bb86',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler',
                customClass: { // Applying theme awareness to Swal
                    popup: document.documentElement.classList.contains('dark') ? 'bg-gray-700 text-gray-200 shadow-xl rounded-lg' : 'bg-white shadow-xl rounded-lg',
                    title: document.documentElement.classList.contains('dark') ? 'text-gray-100' : 'text-gray-800',
                    htmlContainer: document.documentElement.classList.contains('dark') ? 'text-gray-300' : 'text-gray-600',
                    confirmButton: 'inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150',
                    cancelButton: 'ml-3 inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150'
                },
                buttonsStyling: false // Use custom classes for buttons
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
