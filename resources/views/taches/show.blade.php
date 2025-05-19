<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Détails de la Tâche : ') }} {{ Str::limit($tache->titre, 40) }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-content-card { background-color: var(--card-bg-light); color: var(--text-dark); }
        html.dark .page-content-card { background-color: var(--card-bg-dark); color: var(--text-light); }

        .detail-group dt { font-weight: 500; color: var(--text-muted-light); }
        html.dark .detail-group dt { color: var(--text-muted-dark); }
        .detail-group dd { color: var(--text-dark); }
        html.dark .detail-group dd { color: var(--text-light); }

        .prose-themed { /* For nl2br content */
            color: var(--text-dark);
        }
        html.dark .prose-themed {
            color: var(--text-light); /* Basic prose color for dark */
        }
        html.dark .prose-themed p, html.dark .prose-themed ul, html.dark .prose-themed ol {
             color: var(--text-light); /* Ensure lists and paragraphs also get dark theme color */
        }
        /* Use Tailwind's `prose dark:prose-invert` if available and configured in tailwind.config.js, which is better */

        .document-link { color: var(--primary-color); }
        html.dark .document-link { color: var(--secondary-color); }
        .document-sender-info { color: var(--text-muted-light); }
        html.dark .document-sender-info { color: var(--text-muted-dark); }

        /* Action Buttons at the bottom */
        .action-button-yellow { background-color: #F59E0B; color:white; } /* Amber-500 */
        .action-button-yellow:hover { background-color: #D97706; } /* Amber-600 */
        html.dark .action-button-yellow { background-color: #FBBF24; color:var(--text-dark); } /* Amber-400 */
        html.dark .action-button-yellow:hover { background-color: #F59E0B; }

        .action-button-green { background-color: #10B981; color:white; } /* Emerald-500 */
        .action-button-green:hover { background-color: #059669; } /* Emerald-600 */
        html.dark .action-button-green { background-color: #34D399; color:var(--text-dark); } /* Emerald-400 */
        html.dark .action-button-green:hover { background-color: #10B981; }

    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="page-content-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="mb-4">
                        @hasrole('directeur')
                        <a href="{{ route('taches.directeur.index') }}" class="text-sm text-link">← Retour à la liste des tâches (Directeur)</a>
                        @endhasrole
                        @hasrole('employe')
                        <a href="{{ route('taches.employe.index') }}" class="text-sm text-link">← Retour à mes tâches (Employé)</a>
                        @endhasrole
                    </div>

                    <h3 class="text-2xl font-semibold mb-2 text-[var(--text-dark)]">{{ $tache->titre }}</h3>
                    <div class="mb-6 text-sm">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($tache->statut == 'terminee') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($tache->statut == 'en_cours' || $tache->statut == 'en_revision') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200
                            @elseif($tache->statut == 'annulee') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif">
                            {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                        </span>
                    </div>


                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 detail-group">
                        <div> <dt>Assignée à</dt> <dd class="mt-1">{{ $tache->employeAssignee->name }}</dd> </div>
                        <div> <dt>Assignée par</dt> <dd class="mt-1">{{ $tache->assignePar->name }}</dd> </div>
                        <div> <dt>Date d'assignation</dt> <dd class="mt-1">{{ $tache->date_assignation->format('d/m/Y H:i') }}</dd> </div>
                        @if($tache->date_debut_prevue) <div> <dt>Date début prévue</dt> <dd class="mt-1">{{ $tache->date_debut_prevue->format('d/m/Y') }}</dd> </div> @endif
                        @if($tache->date_fin_prevue) <div> <dt>Date fin prévue</dt> <dd class="mt-1">{{ $tache->date_fin_prevue->format('d/m/Y') }}</dd> </div> @endif
                        @if($tache->duree_estimee) <div> <dt>Durée estimée</dt> <dd class="mt-1">{{ $tache->duree_estimee }}</dd> </div> @endif
                        @if($tache->date_completion) <div> <dt>Date de complétion</dt> <dd class="mt-1">{{ $tache->date_completion->format('d/m/Y H:i') }}</dd> </div> @endif
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-[var(--text-dark)]">Description</h4>
                        <div class="mt-2 prose dark:prose-invert max-w-none"> {{-- Using Tailwind prose for rich text --}}
                            {!! nl2br(e($tache->description)) ?: '<p>Aucune description fournie.</p>' !!}
                        </div>
                    </div>

                    @if($tache->documents->count() > 0)
                    <div class="mt-6">
                        <h4 class="text-lg font-medium text-[var(--text-dark)]">Documents Attachés</h4>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach ($tache->documents as $document)
                            <li class="text-sm">
                                <a href="{{ route('taches.document.telecharger', ['tache' => $tache->id, 'document' => $document->id]) }}"
                                   class="document-link hover:underline">
                                    {{ $document->nom_original }}
                                </a>
                                <span class="document-sender-info text-xs">(Soumis par: {{ $document->expediteur->name }})</span>
                                @if($document->description_document) {{-- Check actual field name --}}
                                <p class="text-xs text-gray-500 dark:text-gray-400 pl-4"><em>{{ $document->description_document }}</em></p>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mt-8 flex justify-end space-x-3">
                        @hasrole('directeur')
                            <a href="{{ route('taches.directeur.edit', $tache) }}" class="px-4 py-2 action-button-yellow rounded-md text-xs font-semibold uppercase tracking-widest">Modifier (Directeur)</a>
                        @endhasrole
                        @hasrole('employe')
                            @if($tache->statut !== 'terminee' && $tache->statut !== 'annulee' && $tache->employe_id === Auth::id())
                                <a href="{{ route('taches.realiser.form', $tache) }}" class="px-4 py-2 action-button-green rounded-md text-xs font-semibold uppercase tracking-widest">Mettre à jour / Réaliser</a>
                            @endif
                        @endhasrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
