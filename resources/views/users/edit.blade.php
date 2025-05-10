<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modifier l\'Utilisateur : ') }} {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">

                    @if (count($errors) > 0)
                      <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Oups!</strong>
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
                        @method('PATCH') {{-- Ou PUT, selon votre préférence/convention --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom Complet -->
                            <div class="md:col-span-2">
                                <x-input-label for="name" :value="__('Nom Complet')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <x-input-label for="telephone" :value="__('Téléphone (Optionnel)')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone', $user->telephone)" />
                                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                            </div>

                            <!-- Poste -->
                            <div>
                                <x-input-label for="post" :value="__('Poste (Optionnel)')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="post" class="block mt-1 w-full" type="text" name="post" :value="old('post', $user->post)" />
                                <x-input-error :messages="$errors->get('post')" class="mt-2" />
                            </div>

                             <!-- Statut -->
                            <!-- Dans resources/views/users/edit.blade.php -->
<div>
    <x-input-label for="statut" :value="__('Statut du Compte')" />
    <select name="statut" id="statut" required
            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
        @foreach (['actif', 'inactif', 'en_conge'] as $statut_val)
            <option value="{{ $statut_val }}" {{ old('statut', $user->statut) == $statut_val ? 'selected' : '' }}>
                {{ ucfirst($statut_val) }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('statut')" class="mt-2" />
</div>
                            <!-- Date d'embauche -->
                            <div>
                                <x-input-label for="date_embauche" :value="__('Date d\'embauche (Optionnel)')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="date_embauche" class="block mt-1 w-full" type="date" name="date_embauche" :value="old('date_embauche', $user->date_embauche ? \Carbon\Carbon::parse($user->date_embauche)->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('date_embauche')" class="mt-2" />
                            </div>

                            <!-- Rôles -->
                            <div class="md:col-span-2">
                                <x-input-label for="roles" :value="__('Rôle(s)')" class="text-gray-700 dark:text-gray-300" />
                                <select name="roles[]" id="roles" multiple required
                                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-32">
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ in_array($value, old('roles', $userRole)) ? 'selected' : '' }}>
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
                                    <x-input-label for="password" :value="__('Nouveau Mot de passe')" class="text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Confirmation Mot de passe -->
                            <div class="md:col-span-2">
                                <x-input-label for="password_confirmation" :value="__('Confirmer le Nouveau Mot de passe')" class="text-gray-700 dark:text-gray-300" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M7.5
                                       2.5a2.5 2.5 0 00-4.902.03L2.05 3.779A2.5 2.5 0 003.25 7H3V5.5A2.5 2.5 0 015.5 3h11A2.5 2.5 0 0119 5.5V14.5A2.5 2.5 0 0116.5 17H13V8.5a2.5 2.5 0 00-5 0V17H5.25a2.5 2.5 0 01-1.2-1.221l-.548-1.249A2.5 2.5 0 001 12.5V7.5A2.5 2.5 0 013.5 5H7V2.5zM5 7.5A1.5 1.5 0 013.5 6H2v6.5a1.5 1.5 0 00.779 1.373l.549 1.249A1.5 1.5 0 004.549 16H7V8.5A1.5 1.5 0 018.5 7H13V5.5A1.5 1.5 0 0011.5 4H5.5A1.5 1.5 0 004 5.5V7.5H5z" />
                                </svg>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
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
                    timer: 3000, // Ferme automatiquement après 3 secondes
                    customClass: {
                        popup: 'dark:bg-gray-800 dark:text-gray-200',
                        confirmButton: 'dark:focus:ring-green-800' // Adapter la couleur si besoin
                    }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'dark:bg-gray-800 dark:text-gray-200',
                        confirmButton: 'dark:focus:ring-red-800' // Adapter la couleur si besoin
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
