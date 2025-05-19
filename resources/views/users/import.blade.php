<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Importer des Utilisateurs (Employés) depuis Excel') }}
            </h2>
            <a href="{{ route('users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Main card container for the import form */
        .import-form-card {
            max-width: 700px; /* Or max-w-3xl as per your original template */
            margin: 2rem auto;
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            border-radius: 0.75rem; /* sm:rounded-lg */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); /* shadow-xl */
            padding: 2rem; /* md:p-8 */
            overflow: hidden;
        }
        html.dark .import-form-card {
            background-color: var(--card-bg-dark);
            color: var(--text-light);
        }

        .import-form-card h3 { /* For section titles like "Instructions" */
            font-size: 1.125rem; /* text-lg */
            font-weight: 600; /* font-semibold */
            color: var(--text-dark);
            margin-bottom: 0.5rem; /* mb-2 */
        }
        html.dark .import-form-card h3 {
            color: var(--text-light);
        }

        /* Input Label - If not using x-input-label, or for specific overrides */
        .form-label-custom {
            display: block;
            font-weight: 500; /* Tailwind: font-medium */
            font-size: 0.875rem; /* text-sm */
            color: var(--text-muted-light);
            margin-bottom: 0.25rem;
        }
        html.dark .form-label-custom {
            color: var(--text-muted-dark);
        }

        /* File Input specific styling if needed - Breeze often has custom styling for this component too */
        /* The class `file:bg-indigo-50 dark:file:bg-indigo-800 file:text-indigo-700 dark:file:text-indigo-300 ...` handles theming */
        /* This is more of an example if you had a fully custom file input not using components */
        .custom-file-input {
            /* ... styles ... */
            border-color: var(--border-color-light);
            background-color: var(--content-bg-light); /* or form-accent-bg */
        }
        html.dark .custom-file-input {
            border-color: var(--border-color-dark);
            background-color: var(--content-bg-dark); /* or form-accent-bg-dark */
        }

        /* Help text / instructions text styling */
        .form-help-text {
            font-size: 0.75rem; /* text-xs */
            color: var(--text-muted-light);
        }
        html.dark .form-help-text {
            color: var(--text-muted-dark);
        }
        .form-help-text strong {
            font-weight: 600; /* semibold */
            color: var(--text-dark);
        }
        html.dark .form-help-text strong {
             color: var(--text-light);
        }

        .instructions-list {
            list-style-type: disc;
            list-style-position: inside;
            space-y: 0.25rem; /* Tailwind space-y-1 */
            color: var(--text-muted-light);
        }
        html.dark .instructions-list {
             color: var(--text-muted-dark);
        }
        .instructions-list ul {
            list-style-type: circle;
            list-style-position: inside;
            margin-left: 1rem; /* ml-4 */
        }


        /* Alert styling - Reusing and ensuring theme awareness */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; } /* green */
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; } /* red */
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
        .alert-danger strong { font-weight: bold; }
        .alert-danger ul { margin-top: 0.5rem; list-style-type: disc; padding-left: 1.25rem; font-size: 0.875rem;}

    </style>
    @endpush

    <div class="py-12">
        {{-- Using custom container class to apply themed styles --}}
        <div class="import-form-card">
            {{-- The p-6 md:p-8 from original template is now handled by .import-form-card padding --}}
            {{-- Removed border-b from the inner div, card itself has the border/shadow --}}
            <div>

                @if(session('success'))
                    <div class="alert-container alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-container alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session()->has('import_errors'))
                    <div class="alert-container alert-danger" role="alert">
                        <strong class="font-bold">Des erreurs sont survenues lors de l'importation :</strong>
                        <ul class="mt-2"> {{-- Removed list-disc list-inside if you want to control via Tailwind --}}
                            @foreach(session('import_errors') as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Generic form validation errors (excluding specific excel_file errors already handled) --}}
                @if ($errors->any() && !$errors->has('excel_file') && !session()->has('import_errors'))
                    <div class="alert-container alert-danger" role="alert">
                        <strong class="font-bold">Oups! Des erreurs de validation du formulaire :</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="excel_file" :value="__('Fichier Excel (.xlsx, .xls)')" class="form-label-custom" />
                        {{-- The file input component provided by Breeze (or a custom one) handles its own dark mode styling --}}
                        <input type="file" name="excel_file" id="excel_file" required
                               class="block w-full mt-1 text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 dark:file:bg-indigo-800 file:text-indigo-600 dark:file:text-indigo-300 hover:file:bg-indigo-200 dark:hover:file:bg-indigo-700">
                        <p class="mt-2 form-help-text">
                            Le fichier doit contenir les colonnes : <strong>nom_complet, email</strong> (obligatoires).
                            <br>Optionnel : <strong>mot_de_passe_initial, telephone, poste, statut, date_dembauche</strong>.
                            <br>Format date : JJ/MM/AAAA ou YYYY-MM-DD.
                            <br>Statut : actif, inactif, en_conge.
                        </p>
                        <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-start">
                        {{-- Primary button component from Breeze should be theme aware --}}
                        <x-primary-button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12L12 19" /> <path stroke-linecap="round" stroke-linejoin="round" d="M12 12L9 15m3-3l3 3" />
                            </svg>
                            {{ __('Importer le Fichier') }}
                        </x-primary-button>
                    </div>
                </form>

                 <div class="mt-8 pt-6 border-t border-[var(--border-color-light)]">
                    <h3>Instructions pour le Fichier d'Import</h3>
                    <ul class="instructions-list">
                        <li>La première ligne de votre fichier Excel doit contenir les en-têtes de colonnes.</li>
                        <li>En-têtes attendus (attention à la casse exacte):
                            <ul class="list-disc ml-5">
                                <li><code>nom_complet</code> (Obligatoire)</li>
                                <li><code>email</code> (Obligatoire, doit être unique)</li>
                                <li><code>mot_de_passe_initial</code> (Optionnel, 'password123' par défaut si vide)</li>
                                <li><code>telephone</code> (Optionnel)</li>
                                <li><code>poste</code> (Optionnel)</li>
                                <li><code>statut</code> (Optionnel, 'actif' par défaut. Valeurs : actif, inactif, en_conge)</li>
                                <li><code>date_dembauche</code> (Optionnel, format date ex: 2023-10-26)</li>
                            </ul>
                        </li>
                        <li>Assurez-vous qu'il n'y a pas de lignes vides avant ou entre vos données.</li>
                        <li>Le rôle "employe" sera assigné par défaut aux utilisateurs importés.</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
