<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Importer des Utilisateurs (Employés) depuis Excel') }}
            </h2>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session()->has('import_errors'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" role="alert">
                            <strong class="font-bold">Des erreurs sont survenues lors de l'importation :</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($errors->any() && !$errors->has('excel_file') && !session()->has('import_errors')) {{-- Autres erreurs de validation du formulaire lui-même --}}
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="excel_file" :value="__('Fichier Excel (.xlsx, .xls)')" class="text-gray-700 dark:text-gray-300" />
                            <input type="file" name="excel_file" id="excel_file" required
                                   class="block w-full mt-1 text-sm text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-800 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-700">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Le fichier doit contenir les colonnes : <strong>nom_complet, email</strong> (obligatoires).
                                <br>Optionnel : <strong>mot_de_passe_initial, telephone, poste, statut, date_dembauche</strong>.
                                <br>Format date : JJ/MM/AAAA ou YYYY-MM-DD (Excel doit le reconnaître comme date).
                                <br>Statut : actif, inactif, en_conge.
                            </p>
                            <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-start">
                            <x-primary-button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12L12 19" /> <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L9 15m3-3l3 3" />
                                </svg>
                                {{ __('Importer le Fichier') }}
                            </x-primary-button>
                        </div>
                    </form>

                     <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Instructions pour le Fichier d'Import</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <li>La première ligne de votre fichier Excel doit contenir les en-têtes de colonnes.</li>
                            <li>En-têtes attendus (sensibles à la casse exacte si vous utilisez `WithHeadingRow` par défaut) :
                                <ul class="list-circle list-inside ml-4">
                                    <li><code>nom_complet</code> (Obligatoire)</li>
                                    <li><code>email</code> (Obligatoire, doit être unique)</li>
                                    <li><code>mot_de_passe_initial</code> (Optionnel, 'password123' par défaut si vide)</li>
                                    <li><code>telephone</code> (Optionnel)</li>
                                    <li><code>poste</code> (Optionnel)</li>
                                    <li><code>statut</code> (Optionnel, 'actif' par défaut. Valeurs : actif, inactif, en_conge)</li>
                                    <li><code>date_dembauche</code> (Optionnel, format date reconnu par Excel)</li>
                                </ul>
                            </li>
                            <li>Assurez-vous qu'il n'y a pas de lignes vides avant ou entre vos données.</li>
                            <li>Le rôle "employe" sera assigné par défaut aux utilisateurs importés.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
