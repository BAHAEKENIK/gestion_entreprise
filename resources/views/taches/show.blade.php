<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la Tâche : ') }} {{ $tache->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        @hasrole('directeur')
                        <a href="{{ route('taches.directeur.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Retour à la liste des tâches (Directeur)</a>
                        @endhasrole
                        @hasrole('employe')
                        <a href="{{ route('taches.employe.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Retour à mes tâches (Employé)</a>
                        @endhasrole
                    </div>

                    <h3 class="text-2xl font-semibold text-gray-800">{{ $tache->titre }}</h3>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Assignée à</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->employeAssignee->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Assignée par</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->assignePar->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Statut</p>
                            <p class="mt-1 text-md">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($tache->statut == 'terminee') bg-green-100 text-green-800 @elseif($tache->statut == 'en_cours' || $tache->statut == 'en_revision') bg-yellow-100 text-yellow-800 @elseif($tache->statut == 'annulee') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date d'assignation</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->date_assignation->format('d/m/Y H:i') }}</p>
                        </div>
                         @if($tache->date_debut_prevue)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date début prévue</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->date_debut_prevue->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($tache->date_fin_prevue)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date fin prévue</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->date_fin_prevue->format('d/m/Y') }}</p>
                        </div>
                        @endif
                         @if($tache->duree_estimee)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Durée estimée</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->duree_estimee }}</p>
                        </div>
                        @endif
                        @if($tache->date_completion)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date de complétion</p>
                            <p class="mt-1 text-md text-gray-900">{{ $tache->date_completion->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-700">Description</h4>
                        <div class="mt-2 prose max-w-none text-gray-600">
                            {!! nl2br(e($tache->description)) ?: '<p>Aucune description fournie.</p>' !!}
                        </div>
                    </div>

                    @if($tache->documents->count() > 0)
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-gray-700">Documents Attachés</h4>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach ($tache->documents as $document)
                            <li class="text-sm">
                                <a href="{{ route('taches.document.telecharger', ['tache' => $tache->id, 'document' => $document->id]) }}"
                                   class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                    {{ $document->nom_original }}
                                </a>
                                <span class="text-gray-500 text-xs">(Soumis par: {{ $document->expediteur->name }})</span>
                                @if($document->description)
                                <p class="text-xs text-gray-500 pl-4"><em>{{ $document->description }}</em></p>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mt-8 flex justify-end space-x-3">
                        @hasrole('directeur')
                            <a href="{{ route('taches.directeur.edit', $tache) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Modifier (Directeur)</a>
                        @endhasrole
                        @hasrole('employe')
                            @if($tache->statut !== 'terminee' && $tache->statut !== 'annulee' && $tache->employe_id === Auth::id())
                                <a href="{{ route('taches.realiser.form', $tache) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Mettre à jour / Réaliser</a>
                            @endif
                        @endhasrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
