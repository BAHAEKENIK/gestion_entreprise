<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Rôles') }}
            </h2>
            @can('role-create')
                <a style="color: lightgreen" href="{{ route('roles.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Créer Nouveau Rôle') }}
                </a>
            @endcan
        </div>
    </x-slot>

    {{-- Styles spécifiques pour la page de gestion des rôles --}}
    @push('styles')
    <style>
        .role-management-container {
            font-family: 'Arial', sans-serif; /* Vous pouvez garder une police spécifique si souhaité */
        }

        /* En-tête de la carte de contenu (si vous n'utilisez pas le slot header de Breeze) */
        .role-content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem; /* mb-5 */
            padding-bottom: 1rem; /* pb-4 */
            border-bottom: 1px solid #e5e7eb; /* border-b border-gray-200 */
        }
        .dark .role-content-header {
            border-bottom-color: #374151; /* dark:border-gray-700 */
        }

        .role-content-header h3 {
            margin: 0;
            font-size: 1.5rem; /* text-2xl */
            font-weight: 600; /* font-semibold */
            /* Couleur via classes Tailwind sur l'élément */
        }

        /* Boutons personnalisés (si les classes Tailwind ne suffisent pas) */
        .custom-btn {
            text-decoration: none;
            padding: 0.5rem 0.75rem; /* px-3 py-2 */
            border-radius: 0.375rem; /* rounded-md */
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            display: inline-flex;
            align-items: center;
            gap: 0.375rem; /* space-x-1.5 (environ) */
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
        }

        .custom-btn-success {
            background-color: #28a745; /* Votre vert */
            color: #fff;
        }
        .custom-btn-success:hover {
            background-color: #218838;
            box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.06); /* shadow-md */
        }

        .custom-btn-info {
            background-color: #17a2b8; /* Votre bleu info */
            color: #fff;
        }
        .custom-btn-info:hover {
            background-color: #138496;
             box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.06);
        }

        .custom-btn-primary { /* Utilisé pour Edit */
            background-color: #007bff; /* Votre bleu primaire */
            color: #fff;
        }
        .custom-btn-primary:hover {
            background-color: #0056b3;
             box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.06);
        }

        .custom-btn-danger { /* Utilisé pour Delete */
            background-color: #dc3545; /* Votre rouge danger */
            color: #fff;
        }
        .custom-btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.06);
        }


        /* Alertes personnalisées */
        .custom-alert-success {
            padding: 1rem; /* p-4 */
            background-color: #d1fae5; /* bg-green-100 */
            color: #065f46; /* text-green-700 */
            border: 1px solid #a7f3d0; /* border-green-300 */
            border-radius: 0.375rem; /* rounded-md */
            margin-bottom: 1.5rem; /* mb-6 */
            position: relative;
        }
        .dark .custom-alert-success {
            background-color: #052e16; /* dark:bg-green-900 (approximatif) */
            color: #6ee7b7; /* dark:text-green-300 */
            border-color: #10b981; /* dark:border-green-600 */
        }

        .custom-close-alert {
            position: absolute;
            top: 0.75rem; /* Ajusté */
            right: 0.75rem; /* Ajusté */
            background: none;
            border: none;
            font-size: 1.25rem; /* text-xl */
            font-weight: bold;
            color: inherit; /* Hérite de la couleur du parent (.custom-alert-success) */
            cursor: pointer;
            line-height: 1;
        }
        .custom-close-alert:hover {
            opacity: 0.7;
        }

        /* Table personnalisée */
        .custom-role-table {
            width: 100%;
            border-collapse: collapse; /* Tailwind: border-collapse */
            margin-bottom: 1.5rem; /* mb-6 */
            /* Le fond et l'ombre sont gérés par le conteneur .bg-white de Breeze */
        }

        .custom-role-table th,
        .custom-role-table td {
            padding: 0.75rem 1rem; /* px-4 py-3 */
            text-align: left;
            border-bottom-width: 1px; /* border-b */
            /* Couleur de bordure via classes Tailwind sur la table ou <tr> */
        }

        .custom-role-table th {
            background-color: #4a5568; /* Votre gris foncé, similaire à bg-gray-700 */
            color: #fff; /* text-white */
            font-weight: 600; /* font-semibold */
            font-size: 0.75rem; /* text-xs */
            text-transform: uppercase; /* uppercase */
            letter-spacing: 0.05em; /* tracking-wider */
        }
        .dark .custom-role-table th {
             background-color: #2d3748; /* dark:bg-gray-800 ou plus foncé */
        }

        .custom-role-table tr:hover td { /* Effet de survol pour la ligne */
            background-color: #f7fafc; /* bg-gray-50 */
        }
        .dark .custom-role-table tr:hover td {
            background-color: #252f3e; /* dark:bg-gray-700/50 (un peu plus clair) */
        }

        /* Icônes dans les boutons */
        .custom-btn svg {
            width: 1em; /* Ajuste la taille de l'icône par rapport au texte */
            height: 1em;
            margin-right: 0.375em; /* Espace entre icône et texte */
        }
    </style>
    @endpush

    <div class="py-12 role-management-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    {{-- L'en-tête est maintenant géré par le slot de Breeze --}}

                    @if ($message = Session::get('success'))
                        <div class="custom-alert-success" role="alert">
                            <p>{{ $message }}</p>
                            <button type="button" class="custom-close-alert" aria-label="Close" onclick="this.parentElement.style.display='none';">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif

                    @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table style="width: 100%" class="custom-role-table min-w-full">
                            <thead class="dark:bg-gray-700"> {{-- Tailwind pour le thead --}}
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-gray-300 uppercase tracking-wider bg-gray-700 dark:bg-gray-900">Nom du Rôle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-gray-300 uppercase tracking-wider bg-gray-700 dark:bg-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($roles as $key => $role)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a class="custom-btn custom-btn-info" href="{{ route('roles.show', $role->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Voir
                                            </a>
                                            @can('role-edit')
                                                <a class="custom-btn custom-btn-primary" href="{{ route('roles.edit', $role->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                                    Modifier
                                                </a>
                                            @endcan
                                            @can('role-delete')
                                                <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cela pourrait affecter les utilisateurs assignés.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="custom-btn custom-btn-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.56 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{-- Utilisation de la pagination stylisée par Breeze/Tailwind par défaut --}}
                        {!! $roles->links() !!}
                    </div>
                    @else
                        <p class="text-gray-700 dark:text-gray-300">Aucun rôle trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert pour les messages de session (peut être dans app.blade.php si utilisé globalement) --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark:bg-gray-800 dark:text-gray-200' : '' }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
