<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Utilisateurs (Employés)') }}
            </h2>
            <div class="flex space-x-2">
                @can('user-create') {{-- Ou une permission spécifique 'user-import' --}}
                    <a href="{{ route('users.import.form') }}"
                       class="inline-flex items-center px-4 py-2 bg-teal-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600 active:bg-teal-700 focus:outline-none focus:border-teal-700 focus:ring ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12L12 19" /> <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L9 15m3-3l3 3" />
                        </svg>
                        {{ __('Importer') }}
                    </a>
                @endcan
                @can('user-export') {{-- Ou user-list --}}
                    <a href="{{ route('users.export') }}"
                       class="inline-flex items-center px-4 py-2 bg-sky-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-600 active:bg-sky-700 focus:outline-none focus:border-sky-700 focus:ring ring-sky-300 disabled:opacity-25 transition ease-in-out duration-150">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                       </svg>
                        {{ __('Exporter') }}
                    </a>
                @endcan
                @can('user-create')
                    <a href="{{ route('users.create') }}"
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @include('partials.flash-messages')

                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                            <div class="flex">
                                <input type="text" name="search" class="block w-full sm:w-64 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Rechercher..." value="{{ request('search') }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                             <div class="flex items-center">
                                <label for="per_page" class="mr-2 text-sm text-gray-700 dark:text-gray-300">Afficher:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()"
                                        class="block w-full sm:w-auto px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-200 text-sm">
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
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th> --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom Complet</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rôles</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($users as $user)
                                        <tr>
                                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ ++$i }}</td> --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $v)
                                                        @if($v !== 'directeur') {{-- Ne pas afficher le rôle directeur s'il est filtré --}}
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($user->statut == 'actif') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($user->statut == 'inactif') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                                    {{ ucfirst($user->statut) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1 md:space-x-2">
                                                @can('user-list') {{-- Ou user-show --}}
                                                <a href="{{ route('users.show', $user->id) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Consulter">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                                    <span class="hidden md:inline ml-1">Voir</span>
                                                </a>
                                                @endcan

                                                @can('user-edit')
                                                <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 p-1 rounded hover:bg-yellow-100 dark:hover:bg-yellow-700 transition" title="Modifier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    <span class="hidden md:inline ml-1">Modifier</span>
                                                </a>
                                                @endcan

                                                @if(Auth::user()->id != $user->id) {{-- Un directeur ne peut pas se supprimer lui-même ici --}}
                                                    @can('user-delete')
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $user->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 p-1 rounded hover:bg-red-100 dark:hover:bg-red-700 transition" title="Supprimer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            <span class="hidden md:inline ml-1">Supprimer</span>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                @endif

                                                @if($user->hasRole('employe')) {{-- Ne montrer que pour les employés --}}
                                                    <a href="{{ route('taches.create', ['user' => $user->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 p-1 rounded hover:bg-blue-100 dark:hover:bg-blue-700 transition" title="Affecter une tâche">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                                        <span class="hidden md:inline ml-1">Tâche</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{-- $users->links() sans appends pour ne pas garder le search dans l'URL de pagination si non voulu --}}
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <p class="text-gray-700 dark:text-gray-300">{{ __('Aucun utilisateur (employé) trouvé.') }}</p>
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
                showCancelButton: true,
                confirmButtonColor: '#d33', // Rouge
                cancelButtonColor: '#3085d6', // Bleu
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler',
                customClass: {
                    popup: document.documentElement.classList.contains('dark') ? 'dark:bg-gray-800 dark:text-gray-200' : '',
                    confirmButton: 'px-4 py-2 text-white', // Tailwind classes pour les boutons SweetAlert
                    cancelButton: 'px-4 py-2 text-white'
                },
                buttonsStyling: false // Important pour que customClass fonctionne bien pour les boutons
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
