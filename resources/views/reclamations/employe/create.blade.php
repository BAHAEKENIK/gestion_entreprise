<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Soumettre une Nouvelle Réclamation') }}
            </h2>
             <a href="{{ route('reclamations.index') }}"
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
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    <form method="POST" action="{{ route('reclamations.store') }}" class="space-y-6">
                        @csrf

                        <!-- Destinataire (Directeur) -->
                        <div>
                            <x-input-label for="directeur_id" :value="__('Destinataire (Directeur)')" />
                            <select name="directeur_id" id="directeur_id" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Sélectionner un directeur --</option>
                                @foreach ($directeurs as $directeur)
                                    <option value="{{ $directeur->id }}" {{ old('directeur_id') == $directeur->id ? 'selected' : '' }}>
                                        {{ $directeur->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('directeur_id')" class="mt-2" />
                        </div>

                        <!-- Sujet -->
                        <div>
                            <x-input-label for="sujet" :value="__('Sujet')" />
                            <x-text-input id="sujet" class="block mt-1 w-full" type="text" name="sujet" :value="old('sujet')" required autofocus />
                            <x-input-error :messages="$errors->get('sujet')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description détaillée de la réclamation')" />
                            <textarea id="description" name="description" rows="6" required
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Soumettre la Réclamation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
