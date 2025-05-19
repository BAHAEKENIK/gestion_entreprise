<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Assigner une Nouvelle Tâche') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .form-page-card { /* Main container for the whole page content inside py-12 */
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
        .custom-form-element { /* For inputs, textareas, selects */
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light);
            color: var(--text-dark);
            /* Retain existing Tailwind structural classes (block, mt-1, w-full, rounded-md, shadow-sm) */
        }
        html.dark .custom-form-element {
            background-color: var(--card-bg-dark); /* Slightly lighter than pure content-bg-dark for inputs */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-element::placeholder { color: var(--text-muted-light); }
        html.dark .custom-form-element::placeholder { color: var(--text-muted-dark); }
        .custom-form-element:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3);
            outline: none;
        }
         /* Alert styles for theme awareness */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }

    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="form-page-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8"> {{-- Use theme default text color, overidden by specific element styles where needed --}}
                    <form method="POST" action="{{ route('taches.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="titre" :value="__('Titre de la tâche')" class="text-[var(--text-dark)]" />
                            <x-text-input id="titre" class="custom-form-element block mt-1 w-full" type="text" name="titre" :value="old('titre')" required autofocus />
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description (Optionnel)')" class="text-[var(--text-dark)]" />
                            <textarea id="description" name="description" rows="4"
                                      class="custom-form-element block mt-1 w-full rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="employe_id" :value="__('Assigner à l\'employé')" class="text-[var(--text-dark)]" />
                            <select name="employe_id" id="employe_id" required
                                    class="custom-form-element block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Sélectionner un employé --</option>
                                @foreach ($employes as $employe) {{-- Assume $employes is passed from controller --}}
                                    <option value="{{ $employe->id }}" {{ old('employe_id', $selectedEmployeId ?? '') == $employe->id ? 'selected' : '' }}>
                                        {{ $employe->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employe_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="statut" :value="__('Statut initial')" class="text-[var(--text-dark)]"/>
                            <select name="statut" id="statut" required
                                    class="custom-form-element block mt-1 w-full rounded-md shadow-sm">
                                @foreach ($statuts as $statut_val) {{-- Assume $statuts is passed from controller --}}
                                    <option value="{{ $statut_val }}" {{ old('statut', 'a_faire') == $statut_val ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                             <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="date_debut_prevue" :value="__('Date de début prévue (Optionnel)')" class="text-[var(--text-dark)]" />
                                <x-text-input id="date_debut_prevue" class="custom-form-element block mt-1 w-full" type="date" name="date_debut_prevue" :value="old('date_debut_prevue')" />
                                <x-input-error :messages="$errors->get('date_debut_prevue')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_fin_prevue" :value="__('Date de fin prévue (Optionnel)')" class="text-[var(--text-dark)]" />
                                <x-text-input id="date_fin_prevue" class="custom-form-element block mt-1 w-full" type="date" name="date_fin_prevue" :value="old('date_fin_prevue')" />
                                <x-input-error :messages="$errors->get('date_fin_prevue')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="duree_estimee" :value="__('Durée estimée (ex: 2 heures, 1 jour) (Optionnel)')" class="text-[var(--text-dark)]" />
                            <x-text-input id="duree_estimee" class="custom-form-element block mt-1 w-full" type="text" name="duree_estimee" :value="old('duree_estimee')" />
                            <x-input-error :messages="$errors->get('duree_estimee')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="document_tache" :value="__('Joindre un document (Optionnel)')" class="text-[var(--text-dark)]"/>
                            {{-- File input uses specific Tailwind styling including dark variants, generally okay --}}
                            <input id="document_tache" name="document_tache" type="file" class="block mt-1 w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-800 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-700 cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700"/>
                            <x-input-error :messages="$errors->get('document_tache')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('taches.directeur.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">{{ __('Annuler') }}</a>
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
