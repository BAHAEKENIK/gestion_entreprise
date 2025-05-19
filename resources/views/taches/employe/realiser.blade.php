<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Mettre à Jour / Réaliser la Tâche : ') }} {{ $tache->titre }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .form-page-card { background-color: var(--card-bg-light); color: var(--text-dark); }
        html.dark .form-page-card { background-color: var(--card-bg-dark); color: var(--text-light); }

        .details-preview-section {
            margin-bottom: 1.5rem; padding: 1rem;
            border: 1px solid var(--border-color-light);
            border-radius: 0.375rem;
            background-color: var(--content-bg-light); /* Slightly different for section */
        }
        html.dark .details-preview-section {
             background-color: var(--sidebar-bg-dark); /* Can use sidebar's dark for contrast */
             border-color: var(--border-color-dark);
        }
        .details-preview-section h3 {
            font-size: 1.125rem; font-weight: 500; color: var(--text-dark);
            margin-bottom: 0.75rem; padding-bottom:0.5rem; border-bottom:1px solid var(--border-color-light);
        }
        html.dark .details-preview-section h3 { color: var(--text-light); border-bottom-color:var(--border-color-dark); }
        .details-preview-section p { font-size: 0.875rem; color: var(--text-muted-light); margin-top:0.25rem; }
        html.dark .details-preview-section p { color: var(--text-muted-dark); }
        .details-preview-section strong { font-weight:600; color: var(--text-dark); }
        html.dark .details-preview-section strong { color: var(--text-light); }
        .details-preview-section ul { list-style: disc; list-style-position: inside; margin-left: 1rem; }
        .details-preview-section a { color:var(--primary-color); } html.dark .details-preview-section a { color:var(--secondary-color); }

        .custom-form-element { /* For select, textarea */
            border: 1px solid var(--border-color-light); background-color: var(--content-bg-light); color: var(--text-dark);
        }
        html.dark .custom-form-element { background-color: var(--card-bg-dark); border-color: var(--border-color-dark); color: var(--text-light); }
        .custom-form-element:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); outline: none; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="form-page-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8"> {{-- Consistent padding --}}
                    <div class="details-preview-section">
                        <h3>Détails de la Tâche</h3>
                        <p><strong>Description:</strong> {{ $tache->description ?: 'N/A' }}</p>
                        <p><strong>Assignée par:</strong> {{ $tache->assignePar->name }}</p>
                        <p><strong>Statut Actuel:</strong> <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $tache->statut)) }}</span></p>
                        @if($tache->date_fin_prevue)
                        <p><strong>Date de fin prévue:</strong> {{ $tache->date_fin_prevue->format('d/m/Y') }}</p>
                        @endif
                        @if($tache->documents->where('expediteur_id', $tache->directeur_id)->count() > 0)
                            <p class="mt-2"><strong>Documents joints par le directeur:</strong></p>
                            <ul>
                                @foreach($tache->documents->where('expediteur_id', $tache->directeur_id) as $doc)
                                    <li>
                                        <a href="{{ route('taches.document.telecharger', ['tache' => $tache->id, 'document' => $doc->id]) }}" class="hover:underline">
                                            {{ $doc->nom_original }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('taches.realiser.submit', $tache) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label for="statut" :value="__('Mettre à jour le statut')" class="text-[var(--text-dark)]" />
                            <select name="statut" id="statut" required
                                    class="custom-form-element block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($statutsPossiblesEmploye as $statut_val)
                                    <option value="{{ $statut_val }}" {{ old('statut', $tache->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                             <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description_employe" :value="__('Ajouter un commentaire / Progression (Optionnel)')" class="text-[var(--text-dark)]" />
                            <textarea id="description_employe" name="description_employe" rows="4"
                                      class="custom-form-element block mt-1 w-full rounded-md shadow-sm">{{ old('description_employe') }}</textarea>
                            <x-input-error :messages="$errors->get('description_employe')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="document_realisation" :value="__('Joindre un document de réalisation (Optionnel)')" class="text-[var(--text-dark)]"/>
                            <input id="document_realisation" name="document_realisation" type="file"
                                   class="custom-form-element block mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-800 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-700 cursor-pointer"/>
                            <x-input-error :messages="$errors->get('document_realisation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                             <a href="{{ route('taches.employe.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">{{ __('Annuler') }}</a>
                            <x-primary-button>
                                {{ __('Soumettre la Mise à Jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
