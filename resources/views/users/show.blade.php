<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Utilisateur : ') }} {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    {{-- Styles spécifiques pour cette page (pour les animations et le look "carte" si vous voulez un style distinct) --}}
    <style>
        .details-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .details-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .detail-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .detail-item.is-visible {
            opacity: 1;
            transform: translateX(0);
        }
    </style>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div id="user-details-card" class="details-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 bg-gradient-to-r from-pink-600 to-red-500 dark:from-pink-700 dark:to-red-600">
                    <h3 class="text-lg leading-6 font-semibold text-white">
                        Informations de {{ $user->name }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nom Complet</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                        </div>
                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                        </div>

                        @if($user->telephone)
                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Téléphone</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->telephone }}</dd>
                        </div>
                        @endif

                        @if($user->post)
                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Poste</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->post }}</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut du Compte</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($user->statut == 'actif') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                    @elseif($user->statut == 'inactif') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                    {{ ucfirst($user->statut) }}
                                </span>
                            </dd>
                        </div>

                        @if($user->date_embauche)
                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'embauche</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y') }}</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-2 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rôle(s)</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $roleName)
                                        <span class="inline-block bg-indigo-100 dark:bg-indigo-700 text-indigo-700 dark:text-indigo-200 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    @endforeach
                                @else
                                    Aucun rôle assigné.
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Thème Préféré</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ ucfirst($user->theme) }}</dd>
                        </div>

                        <div class="sm:col-span-1 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Doit Changer Mot de Passe</dt>
                            <dd class="mt-1 text-md {{ $user->must_change_password ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-green-600 dark:text-green-400' }}">
                                {{ $user->must_change_password ? 'Oui' : 'Non' }}
                            </dd>
                        </div>

                         <div class="sm:col-span-2 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Créé le</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                         <div class="sm:col-span-2 detail-item">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis à jour le</dt>
                            <dd class="mt-1 text-md text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>

                    </dl>
                </div>
                @can('user-edit') {{-- Affiche le bouton Modifier seulement si l'utilisateur a la permission --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <a href="{{ route('users.edit', $user->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        {{ __('Modifier cet utilisateur') }}
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const card = document.getElementById('user-details-card');
            if (card) {
                // Délai pour l'animation de la carte principale
                setTimeout(() => {
                    card.classList.add('is-visible');
                }, 100); // Délai initial pour la carte

                // Délai pour les éléments enfants
                const detailItems = card.querySelectorAll('.detail-item');
                detailItems.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('is-visible');
                    }, 200 + index * 100); // Délai initial pour la carte + délai progressif pour chaque item
                });
            }

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    customClass: { popup: 'dark:bg-gray-800 dark:text-gray-200' }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    customClass: { popup: 'dark:bg-gray-800 dark:text-gray-200' }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
