<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assigner une Nouvelle Tâche') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('taches.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Titre -->
                        <div>
                            <x-input-label for="titre" :value="__('Titre de la tâche')" />
                            <x-text-input id="titre" class="block mt-1 w-full" type="text" name="titre" :value="old('titre')" required autofocus />
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description (Optionnel)')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Employé -->
                        <div class="mt-4">
                            <x-input-label for="employe_id" :value="__('Assigner à l\'employé')" />
                            <select name="employe_id" id="employe_id" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Sélectionner un employé --</option>
                                @foreach ($employes as $employe)
                                    <option value="{{ $employe->id }}" {{ old('employe_id', $selectedEmployeId) == $employe->id ? 'selected' : '' }}>
                                        {{ $employe->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employe_id')" class="mt-2" />
                        </div>

                        <!-- Statut Initial -->
                        <div class="mt-4">
                            <x-input-label for="statut" :value="__('Statut initial')" />
                            <select name="statut" id="statut" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($statuts as $statut_val)
                                    <option value="{{ $statut_val }}" {{ old('statut', 'a_faire') == $statut_val ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                             <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="date_debut_prevue" :value="__('Date de début prévue (Optionnel)')" />
                                <x-text-input id="date_debut_prevue" class="block mt-1 w-full" type="date" name="date_debut_prevue" :value="old('date_debut_prevue')" />
                                <x-input-error :messages="$errors->get('date_debut_prevue')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_fin_prevue" :value="__('Date de fin prévue (Optionnel)')" />
                                <x-text-input id="date_fin_prevue" class="block mt-1 w-full" type="date" name="date_fin_prevue" :value="old('date_fin_prevue')" />
                                <x-input-error :messages="$errors->get('date_fin_prevue')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Durée Estimée -->
                        <div class="mt-4">
                            <x-input-label for="duree_estimee" :value="__('Durée estimée (ex: 2 heures, 1 jour) (Optionnel)')" />
                            <x-text-input id="duree_estimee" class="block mt-1 w-full" type="text" name="duree_estimee" :value="old('duree_estimee')" />
                            <x-input-error :messages="$errors->get('duree_estimee')" class="mt-2" />
                        </div>

                        <!-- Document (Optionnel) -->
                        <div class="mt-4">
                            <x-input-label for="document_tache" :value="__('Joindre un document (Optionnel)')" />
                            <input id="document_tache" name="document_tache" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                            <x-input-error :messages="$errors->get('document_tache')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('taches.directeur.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Annuler') }}</a>
                            <x-primary-button>
                                {{ __('Assigner la Tâche') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
