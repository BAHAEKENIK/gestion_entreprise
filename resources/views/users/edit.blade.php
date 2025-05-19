<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Text color now uses theme variables for consistency if not handled by Breeze's default dark:text-gray-200 --}}
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Modifier l\'Utilisateur : ') }} {{ $user->name }}
            </h2>
            {{-- The button here is using Tailwind classes and should be theme-aware if your Tailwind dark mode is set up --}}
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* This custom style block is to ensure the card container for the form respects the theme */
        .form-edit-card-container { /* Specific class for this edit form container */
            max-width: 700px; /* Or max-w-3xl like in the original template */
            margin: 2rem auto; /* Adjust margin as needed, py-12 from original is approx 3rem */
            background-color: var(--card-bg-light); /* Uses theme variable from app.blade.php */
            color: var(--text-dark); /* Default text color for the card content */
            border-radius: 0.75rem; /* sm:rounded-lg from original template */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); /* shadow-xl */
            overflow: hidden; /* As per original sm:rounded-lg might imply overflow hidden for content */
        }

        /* Breeze components x-input-label, x-text-input, x-primary-button already handle dark mode */
        /* This custom style section is mainly if you want a themed "card" wrapper */
        /* Or if some elements within the form (not covered by Breeze components) need theming */

        /* Example: Customizing the general paragraph text color inside the card if not covered by Breeze dark text */
        .form-edit-card-container p {
            color: var(--text-muted-light); /* Default to a muted text */
        }
        html.dark .form-edit-card-container p {
             color: var(--text-muted-dark);
        }

        /* Alert custom styling for theme awareness */
        .alert-custom-danger {
            background-color: #FEF2F2; /* red-50 */
            color: #991B1B; /* red-700 */
            border: 1px solid #FECACA; /* red-300 */
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        html.dark .alert-custom-danger {
            background-color: var(--card-bg-dark); /* Or a specific dark error bg */
            color: #FCA5A5; /* Lighter red for dark mode text */
            border: 1px solid #B91C1C; /* Darker red border */
        }
        .alert-custom-danger strong { font-weight: 700; }
        .alert-custom-danger ul { list-style-type: disc; padding-left: 1.25rem; margin-top: 0.5rem; }

    </style>
    @endpush

    <div class="py-12">
        {{-- max-w-3xl from original template ensures it doesn't get too wide --}}
        <div class="form-edit-card-container"> {{-- Applied the new class for the card wrapper --}}
            <div class="p-6 md:p-8"> {{-- Removed border from original for cleaner card look --}}

                @if (count($errors) > 0)
                  <div class="alert-custom-danger">
                    <strong>Oups!</strong>
                    <span class="block sm:inline">Des erreurs sont survenues :</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                       @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                       @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom Complet -->
                        <div class="md:col-span-2">
                            <x-input-label for="name" :value="__('Nom Complet')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <x-input-label for="telephone" :value="__('Téléphone (Optionnel)')" />
                            <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone', $user->telephone)" />
                            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                        </div>

                        <!-- Poste -->
                        <div>
                            <x-input-label for="post" :value="__('Poste (Optionnel)')" />
                            <x-text-input id="post" class="block mt-1 w-full" type="text" name="post" :value="old('post', $user->post)" />
                            <x-input-error :messages="$errors->get('post')" class="mt-2" />
                        </div>

                         <!-- Statut -->
                        <div>
                            <x-input-label for="statut" :value="__('Statut du Compte')" />
                            <select name="statut" id="statut" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                {{-- Assuming $statuts is passed to the view, otherwise provide a default array --}}
                                @foreach (['actif' => 'Actif', 'inactif' => 'Inactif', 'en_conge' => 'En Congé'] as $statut_val => $statut_label)
                                    <option value="{{ $statut_val }}" {{ old('statut', $user->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ $statut_label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <!-- Date d'embauche -->
                        <div>
                            <x-input-label for="date_embauche" :value="__('Date d\'embauche (Optionnel)')" />
                            <x-text-input id="date_embauche" class="block mt-1 w-full" type="date" name="date_embauche" :value="old('date_embauche', $user->date_embauche ? \Carbon\Carbon::parse($user->date_embauche)->format('Y-m-d') : '')" />
                            <x-input-error :messages="$errors->get('date_embauche')" class="mt-2" />
                        </div>

                        <!-- Rôles -->
                        <div class="md:col-span-2">
                            <x-input-label for="roles" :value="__('Rôle(s)')" />
                            <select name="roles[]" id="roles" multiple required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-32">
                                @foreach($roles as $value => $label) {{-- Assuming $roles is passed to the view --}}
                                    <option value="{{ $value }}" {{ (is_array(old('roles')) && in_array($value, old('roles'))) || (is_array($userRole) && in_array($value, $userRole)) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="block mt-1 text-xs text-gray-500 dark:text-gray-400">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs rôles.</small>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                            <x-input-error :messages="$errors->get('roles.*')" class="mt-2" />
                        </div>

                        <!-- Mot de passe -->
                        <div class="md:col-span-2 mt-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Laissez vide si vous ne souhaitez pas changer le mot de passe.</p>
                            <div>
                                <x-input-label for="password" :value="__('Nouveau Mot de passe')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Confirmation Mot de passe -->
                        <div class="md:col-span-2">
                            <x-input-label for="password_confirmation" :value="__('Confirmer le Nouveau Mot de passe')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        {{-- Breeze primary button should already be theme aware if Tailwind is configured --}}
                        <x-primary-button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M7.5 2.5a2.5 2.5 0 00-4.902.03L2.05 3.779A2.5 2.5 0 003.25 7H3V5.5A2.5 2.5 0 015.5 3h11A2.5 2.5 0 0119 5.5V14.5A2.5 2.5 0 0116.5 17H13V8.5a2.5 2.5 0 00-5 0V17H5.25a2.5 2.5 0 01-1.2-1.221l-.548-1.249A2.5 2.5 0 001 12.5V7.5A2.5 2.5 0 013.5 5H7V2.5zM5 7.5A1.5 1.5 0 013.5 6H2v6.5a1.5 1.5 0 00.779 1.373l.549 1.249A1.5 1.5 0 004.549 16H7V8.5A1.5 1.5 0 018.5 7H13V5.5A1.5 1.5 0 0011.5 4H5.5A1.5 1.5 0 004 5.5V7.5H5z" />
                            </svg>
                            {{ __('Mettre à jour') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    customClass: {
                        popup: document.documentElement.classList.contains('dark') ? 'bg-gray-800 text-gray-200' : 'bg-white',
                        confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white', // Example
                        // Add other classes as needed for specific elements of Swal
                    }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                     customClass: {
                        popup: document.documentElement.classList.contains('dark') ? 'bg-gray-800 text-gray-200' : 'bg-white',
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white',
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
