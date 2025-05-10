<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mettre à Jour / Réaliser la Tâche : ') }} {{ $tache->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md">
                        <h3 class="text-lg font-medium text-gray-900">Détails de la Tâche</h3>
                        <p class="mt-1 text-sm text-gray-600"><strong>Description:</strong> {{ $tache->description ?: 'N/A' }}</p>
                        <p class="mt-1 text-sm text-gray-600"><strong>Assignée par:</strong> {{ $tache->assignePar->name }}</p>
                        <p class="mt-1 text-sm text-gray-600"><strong>Statut Actuel:</strong> <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $tache->statut)) }}</span></p>
                        @if($tache->date_fin_prevue)
                        <p class="mt-1 text-sm text-gray-600"><strong>Date de fin prévue:</strong> {{ $tache->date_fin_prevue->format('d/m/Y') }}</p>
                        @endif
                        @if($tache->documents->count() > 0)
                            <p class="mt-1 text-sm text-gray-600"><strong>Documents joints par le directeur:</strong></p>
                            <ul class="list-disc list-inside ml-4 text-sm">
                                @foreach($tache->documents->where('expediteur_id', $tache->directeur_id) as $doc)
                                    <li>
                                        <a href="{{ route('taches.document.telecharger', ['tache' => $tache->id, 'document' => $doc->id]) }}" class="text-blue-500 hover:underline">
                                            {{ $doc->nom_original }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('taches.realiser.submit', $tache) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mt-4">
                            <x-input-label for="statut" :value="__('Mettre à jour le statut')" />
                            <select name="statut" id="statut" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($statutsPossiblesEmploye as $statut_val)
                                    <option value="{{ $statut_val }}" {{ old('statut', $tache->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                             <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="description_employe" :value="__('Ajouter un commentaire / Progression (Optionnel)')" />
                            <textarea id="description_employe" name="description_employe" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description_employe') }}</textarea>
                            <x-input-error :messages="$errors->get('description_employe')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="document_realisation" :value="__('Joindre un document de réalisation (Optionnel)')" />
                            <input id="document_realisation" name="document_realisation" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                            <x-input-error :messages="$errors->get('document_realisation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('taches.employe.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Annuler') }}</a>
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
