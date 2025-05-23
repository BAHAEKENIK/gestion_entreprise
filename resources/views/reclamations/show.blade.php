<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails de la Réclamation : ') }} <span class="text-indigo-600 dark:text-indigo-400">{{ Str::limit($reclamation->sujet, 50) }}</span>
            </h2>
            <a href="{{ route('reclamations.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sujet</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $reclamation->sujet }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Auteur (Employé)</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->auteur->name }} ({{ $reclamation->auteur->email }})</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Destinataire (Directeur)</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->destinataire->name }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de Soumission</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                            <dd class="mt-1 text-sm">
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($reclamation->statut == 'soumise') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                    @elseif($reclamation->statut == 'en_cours_traitement') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                    @elseif($reclamation->statut == 'resolue') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                    @elseif($reclamation->statut == 'rejetee') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                    @endif">
                                    {{ Str::ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                </span>
                            </dd>
                        </div>

                        <div class="sm:col-span-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description de la Réclamation</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">
                                {!! nl2br(e($reclamation->description)) !!}
                            </dd>
                        </div>

                        @if($reclamation->reponse)
                        <div class="sm:col-span-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Réponse du Directeur</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">
                                {!! nl2br(e($reclamation->reponse)) !!}
                            </dd>
                            @if($reclamation->date_reponse)
                            <dd class="mt-1 text-xs text-gray-500 dark:text-gray-400">Répondu le : {{ $reclamation->date_reponse->format('d/m/Y H:i:s') }}</dd>
                            @endif
                        </div>
                        @endif
                    </dl>

                    @if(Auth::user()->hasRole('directeur') && ($reclamation->statut == 'soumise' || $reclamation->statut == 'en_cours_traitement') && $reclamation->directeur_id == Auth::id())
                        @can('reclamation-traiter-directeur')
                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('reclamations.edit', $reclamation) }}"
                               class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Traiter cette Réclamation') }}
                            </a>
                        </div>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
