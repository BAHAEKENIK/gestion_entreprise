<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Modifier la Tâche : ') }} {{ $tache->titre }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        /* Reusing styles from create.blade.php or user/index for themed card & inputs */
        .form-page-card {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
        .custom-form-element {
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light);
            color: var(--text-dark);
        }
        html.dark .custom-form-element {
            background-color: var(--card-bg-dark);
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
        /* Alert styling (reused from create.blade.php if you have one there) */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="form-page-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    {{-- Flash messages if needed --}}
                    @if (session('success'))
                        <div class="alert-container alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                     @if ($errors->any())
                        <div class="alert-container alert-danger" role="alert">
                            <strong class="font-bold">Oups! Des erreurs de validation sont survenues.</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('taches.directeur.update', $tache) }}">
                        @csrf
                        @method('PUT') {{-- Make sure this matches the route definition --}}

                        <div>
                            <x-input-label for="titre" :value="__('Titre de la tâche')" class="text-[var(--text-dark)]" />
                            <x-text-input id="titre" class="custom-form-element block mt-1 w-full" type="text" name="titre" :value="old('titre', $tache->titre)" required autofocus />
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description (Optionnel)')" class="text-[var(--text-dark)]" />
                            <textarea id="description" name="description" rows="4"
                                      class="custom-form-element block mt-1 w-full rounded-md shadow-sm">{{ old('description', $tache->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="employe_id" :value="__('Assigner à l\'employé')" class="text-[var(--text-dark)]" />
                            <select name="employe_id" id="employe_id" required
                                    class="custom-form-element block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Sélectionner un employé --</option>
                                @foreach ($employes as $employe) {{-- Ensure $employes is passed --}}
                                    <option value="{{ $employe->id }}" {{ old('employe_id', $tache->employe_id) == $employe->id ? 'selected' : '' }}>
                                        {{ $employe->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employe_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="statut" :value="__('Statut')" class="text-[var(--text-dark)]" />
                            <select name="statut" id="statut" required
                                    class="custom-form-element block mt-1 w-full rounded-md shadow-sm">
                                @foreach ($statuts as $statut_val) {{-- Ensure $statuts is passed --}}
                                    <option value="{{ $statut_val }}" {{ old('statut', $tache->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                             <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="date_debut_prevue" :value="__('Date de début prévue (Optionnel)')" class="text-[var(--text-dark)]"/>
                                <x-text-input id="date_debut_prevue" class="custom-form-element block mt-1 w-full" type="date" name="date_debut_prevue" :value="old('date_debut_prevue', $tache->date_debut_prevue ? $tache->date_debut_prevue->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('date_debut_prevue')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_fin_prevue" :value="__('Date de fin prévue (Optionnel)')" class="text-[var(--text-dark)]"/>
                                <x-text-input id="date_fin_prevue" class="custom-form-element block mt-1 w-full" type="date" name="date_fin_prevue" :value="old('date_fin_prevue', $tache->date_fin_prevue ? $tache->date_fin_prevue->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('date_fin_prevue')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="duree_estimee" :value="__('Durée estimée (ex: 2 heures, 1 jour) (Optionnel)')" class="text-[var(--text-dark)]" />
                            <x-text-input id="duree_estimee" class="custom-form-element block mt-1 w-full" type="text" name="duree_estimee" :value="old('duree_estimee', $tache->duree_estimee)" />
                            <x-input-error :messages="$errors->get('duree_estimee')" class="mt-2" />
                        </div>

                        {{-- Document management for edit can be more complex (show existing, allow removal/replacement) - Simplified for now --}}


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('taches.directeur.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">{{ __('Annuler') }}</a>
                            <x-primary-button>
                                {{ __('Mettre à Jour la Tâche') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
