<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Traiter la Réclamation : ') }} <span class="text-indigo-600 dark:text-indigo-400">{{ Str::limit($reclamation->sujet, 50) }}</span>
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    {{-- Affichage des détails de la réclamation --}}
                    <div class="mb-8 p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Détails de la Réclamation</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Auteur</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->auteur->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de soumission</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sujet</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reclamation->sujet }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description initiale</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">{!! nl2br(e($reclamation->description)) !!}</dd>
                            </div>
                             <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut actuel</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
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
                        </dl>
                    </div>

                    <form method="POST" action="{{ route('reclamations.update', $reclamation) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Réponse du Directeur -->
                        <div>
                            <x-input-label for="reponse" :value="__('Votre Réponse')" />
                            <textarea id="reponse" name="reponse" rows="6"
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('reponse', $reclamation->reponse) }}</textarea>
                            <x-input-error :messages="$errors->get('reponse')" class="mt-2" />
                        </div>

                        <!-- Nouveau Statut -->
                        <div>
                            <x-input-label for="statut" :value="__('Changer le Statut')" />
                            <select name="statut" id="statut" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @foreach ($statuts as $statut_val) {{-- $statuts passés par le contrôleur --}}
                                    <option value="{{ $statut_val }}" {{ old('statut', $reclamation->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ Str::ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Mettre à Jour la Réclamation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
