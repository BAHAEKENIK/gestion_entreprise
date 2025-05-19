<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- The text color here already uses dark:text-gray-200 from Breeze defaults --}}
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de l\'Utilisateur : ') }} {{ $user->name }}
            </h2>
            {{-- This button uses Tailwind classes which should be theme-aware --}}
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-600 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .details-card-wrapper { /* New wrapper for the card container */
            background-color: var(--card-bg-light); /* Uses theme variable from app.blade.php */
            color: var(--text-dark); /* Default text for content within this card */
            /* Tailwind classes sm:rounded-lg handle border-radius */
            /* Tailwind shadow-xl is applied directly in the HTML below */
        }
        /* The html.dark selector in app.blade.php's style will handle swapping the variable values */

        .details-card-gradient-header {
            /* Using fixed gradient as per original, but you could make these vars too */
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
            --tw-gradient-from: #EC4899; /* pink-600 */
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(236, 72, 153, 0));
            --tw-gradient-to: #EF4444; /* red-500 */
        }
        html.dark .details-card-gradient-header {
            --tw-gradient-from: #DB2777; /* pink-700 */
            --tw-gradient-to: #DC2626; /* red-600 */
        }
        .details-card-gradient-header h3 {
            color: white; /* Text on gradient header is white */
        }


        /* Specific text elements inside the card if they don't use Tailwind dark: variants properly */
        .details-card-wrapper dt { /* <dt> elements */
            color: var(--text-muted-light);
        }
        html.dark .details-card-wrapper dt {
            color: var(--text-muted-dark);
        }
        .details-card-wrapper dd { /* <dd> elements */
            color: var(--text-dark);
        }
        html.dark .details-card-wrapper dd {
            color: var(--text-light);
        }

        /* Themed button example for an "Edit" button if it wasn't a Breeze component */
        .btn-edit-themed {
            background-color: #F59E0B; /* amber-500 */
            border-color: #F59E0B;
            color: white;
            /* other button styles from your existing .btn-submit-custom could be added here */
        }
        .btn-edit-themed:hover { background-color: #D97706; /* amber-600 */ }
        html.dark .btn-edit-themed {
            background-color: #FBBF24; /* amber-400 */
            border-color: #FBBF24;
            color: var(--text-dark); /* Dark text on lighter amber for dark mode */
        }
         html.dark .btn-edit-themed:hover { background-color: #F59E0B; }


        /* Animation styles (usually theme-independent) */
        .details-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .details-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .detail-item { /* Applied to individual data items for staggered animation */
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .detail-item.is-visible {
            opacity: 1;
            transform: translateX(0);
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Applied custom class for themed background and default text color --}}
            <div id="user-details-card" class="details-card details-card-wrapper overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 details-card-gradient-header"> {{-- Applied custom class for gradient header --}}
                    <h3 class="text-lg leading-6 font-semibold"> {{-- text-white is handled by .details-card-gradient-header h3 --}}
                        Informations de {{ $user->name }}
                    </h3>
                </div>
                <div class="border-t border-[var(--border-color-light)] px-6 py-5"> {{-- Using var for border --}}
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1 detail-item">
                            <dt>Nom Complet</dt>
                            <dd class="mt-1 text-md">{{ $user->name }}</dd>
                        </div>
                        <div class="sm:col-span-1 detail-item">
                            <dt>Email</dt>
                            <dd class="mt-1 text-md">{{ $user->email }}</dd>
                        </div>

                        @if($user->telephone)
                        <div class="sm:col-span-1 detail-item">
                            <dt>Téléphone</dt>
                            <dd class="mt-1 text-md">{{ $user->telephone }}</dd>
                        </div>
                        @endif

                        @if($user->post)
                        <div class="sm:col-span-1 detail-item">
                            <dt>Poste</dt>
                            <dd class="mt-1 text-md">{{ $user->post }}</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-1 detail-item">
                            <dt>Statut du Compte</dt>
                            <dd class="mt-1 text-md">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($user->statut == 'actif') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200
                                    @elseif($user->statut == 'inactif') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200 @endif">
                                    {{ ucfirst($user->statut ?? 'N/A') }}
                                </span>
                            </dd>
                        </div>

                        @if($user->date_embauche)
                        <div class="sm:col-span-1 detail-item">
                            <dt>Date d'embauche</dt>
                            <dd class="mt-1 text-md">{{ \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y') }}</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-2 detail-item">
                            <dt>Rôle(s)</dt>
                            <dd class="mt-1 text-md">
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $roleName)
                                        <span class="inline-block bg-indigo-100 dark:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    @endforeach
                                @else
                                    Aucun rôle assigné.
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1 detail-item">
                            <dt>Thème Préféré</dt>
                            <dd class="mt-1 text-md">{{ ucfirst($user->theme ?? 'light') }}</dd>
                        </div>

                        <div class="sm:col-span-1 detail-item">
                            <dt>Doit Changer Mot de Passe</dt>
                            <dd class="mt-1 text-md {{ $user->must_change_password ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-green-600 dark:text-green-400' }}">
                                {{ $user->must_change_password ? 'Oui' : 'Non' }}
                            </dd>
                        </div>

                         <div class="sm:col-span-2 detail-item">
                            <dt>Créé le</dt>
                            <dd class="mt-1 text-md">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                         <div class="sm:col-span-2 detail-item">
                            <dt>Mis à jour le</dt>
                            <dd class="mt-1 text-md">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
                @can('user-edit')
                <div class="px-6 py-4 border-t border-[var(--border-color-light)] flex justify-end">
                     {{-- The button below uses Tailwind classes for theming and should adapt with html.dark --}}
                    <a href="{{ route('users.edit', $user->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 active:bg-yellow-700 dark:active:bg-yellow-700 focus:outline-none focus:border-yellow-700 dark:focus:border-yellow-600 focus:ring ring-yellow-300 dark:focus:ring-yellow-700 disabled:opacity-25 transition ease-in-out duration-150">
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
                setTimeout(() => { card.classList.add('is-visible'); }, 100);
                const detailItems = card.querySelectorAll('.detail-item');
                detailItems.forEach((el, index) => {
                    setTimeout(() => { el.classList.add('is-visible'); }, 200 + index * 70); // Reduced stagger time slightly
                });
            }

            // SweetAlert2 theme awareness based on <html> class (managed by app.blade.php's Alpine store)
            const isDarkMode = document.documentElement.classList.contains('dark');
            const swalPopupClass = isDarkMode ? 'bg-gray-700 text-gray-200' : 'bg-white'; // Example, adjust for better dark Swal

            @if (session('success'))
                Swal.fire({
                    icon: 'success', title: 'Succès!', text: '{{ session('success') }}', confirmButtonText: 'OK', timer: 3000,
                    customClass: { popup: swalPopupClass }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error', title: 'Erreur!', text: '{{ session('error') }}', confirmButtonText: 'OK',
                    customClass: { popup: swalPopupClass }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
