<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Gestion des Rôles') }}
            </h2>
            @can('role-create')
                <a href="{{ route('roles.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 dark:hover:bg-green-500 active:bg-green-700 dark:active:bg-green-700 focus:outline-none focus:border-green-700 dark:focus:border-green-600 focus:ring ring-green-300 dark:focus:ring-green-700 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Créer Nouveau Rôle') }}
                </a>
            @endcan
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-content-card {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            /* Breeze's shadow-xl sm:rounded-lg applied on the div */
        }
         html.dark .page-content-card {
             background-color: var(--card-bg-dark);
             color: var(--text-light);
        }

        /* Table specific theming */
        .roles-table {
            width: 100%;
            border-collapse: collapse;
        }
        .roles-table thead {
            background-color: var(--sidebar-bg-light); /* Lightest background for header */
        }
        html.dark .roles-table thead {
            background-color: var(--sidebar-bg-dark); /* Darker for dark mode */
        }
        .roles-table th {
            padding: 0.75rem 1.5rem; /* px-6 py-3 */
            text-align: left;
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
            color: var(--text-muted-light);
            text-transform: uppercase;
            letter-spacing: 0.05em; /* tracking-wider */
        }
        html.dark .roles-table th {
            color: var(--text-muted-dark);
        }
        .roles-table tbody {
            background-color: var(--card-bg-light);
            /* divide-y and border colors are handled by vars */
        }
        html.dark .roles-table tbody {
            background-color: var(--card-bg-dark);
        }
        .roles-table td {
            padding: 1rem 1.5rem; /* px-6 py-4 */
            white-space: nowrap;
            font-size: 0.875rem; /* text-sm */
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color-light);
        }
        html.dark .roles-table td {
            color: var(--text-light);
            border-bottom: 1px solid var(--border-color-dark);
        }
        .roles-table tr:hover td { /* Hover effect for rows */
            background-color: var(--sidebar-active-bg-light); /* Using sidebar active color for subtle hover */
        }
        html.dark .roles-table tr:hover td {
            background-color: var(--sidebar-active-bg-dark);
        }


        /* Action buttons with theme variables */
        .action-btn {
            padding: 0.35rem 0.7rem; font-size: 0.8rem; border-radius: 0.25rem;
            text-decoration: none; display: inline-flex; align-items: center;
            font-weight: 500; margin-right: 0.5rem;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s;
            border: 1px solid transparent;
        }
        .action-btn svg { width: 0.9em; height: 0.9em; margin-right: 0.3em; }

        .action-btn-info {
            background-color: var(--content-bg-light); color: var(--primary-color); border-color: var(--primary-color);
        }
        .action-btn-info:hover { background-color: var(--primary-color); color: var(--text-light) !important; }
        html.dark .action-btn-info { background-color: var(--card-bg-dark); color: var(--secondary-color); border-color: var(--secondary-color); }
        html.dark .action-btn-info:hover { background-color: var(--secondary-color); color: var(--text-dark) !important; }

        .action-btn-edit {
            background-color: var(--content-bg-light); color: #D97706; border-color: #D97706; /* amber-600 */
        }
        .action-btn-edit:hover { background-color: #D97706; color: white !important; }
        html.dark .action-btn-edit { background-color: var(--card-bg-dark); color: #FBBF24; border-color: #FBBF24; /* amber-400 */ }
        html.dark .action-btn-edit:hover { background-color: #FBBF24; color: #78350F !important; /* amber-900 text on amber-400 bg */ }

        .action-btn-delete {
            background-color: var(--content-bg-light); color: #DC2626; border-color: #DC2626; /* red-600 */
        }
        .action-btn-delete:hover { background-color: #DC2626; color: white !important; }
        html.dark .action-btn-delete { background-color: var(--card-bg-dark); color: #F87171; border-color: #F87171; /* red-400 */ }
        html.dark .action-btn-delete:hover { background-color: #F87171; color: #7F1D1D !important; }

        /* Alert styling (from create.blade.php) */
        .alert-success-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; position:relative;}
        html.dark .alert-success-container { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .custom-close-alert { position: absolute; top: 0.5rem; right: 0.75rem; background: none; border: none; font-size: 1.25rem; font-weight: bold; color: inherit; cursor: pointer; line-height: 1;}
        .custom-close-alert:hover { opacity: 0.7;}

    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-content-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    {{-- Flash messages already handled by Breeze or custom solution in your app layout --}}
                    @if ($message = Session::get('success'))
                        <div class="alert-success-container" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="custom-close-alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif

                    @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="roles-table min-w-full">
                            <thead>
                                <tr>
                                    <th>Nom du Rôle</th>
                                    <th class="w-1/3">Actions</th> {{-- Giving actions column more defined width --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td class="font-medium">{{ $role->name }}</td>
                                    <td>
                                        <a class="action-btn action-btn-info" href="{{ route('roles.show', $role->id) }}">
                                           <i class="fas fa-eye"></i> <span class="hidden sm:inline">Voir</span>
                                        </a>
                                        @can('role-edit')
                                            <a class="action-btn action-btn-edit" href="{{ route('roles.edit', $role->id) }}">
                                                <i class="fas fa-pencil-alt"></i> <span class="hidden sm:inline">Modifier</span>
                                            </a>
                                        @endcan
                                        @can('role-delete')
                                            @if($role->name !== 'directeur' && $role->name !== 'employe') {{-- Prevent deletion of core roles --}}
                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-delete">
                                                    <i class="fas fa-trash-alt"></i> <span class="hidden sm:inline">Supprimer</span>
                                                </button>
                                            </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {!! $roles->links() !!}
                    </div>
                    @else
                        <p>Aucun rôle trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
