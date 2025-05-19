<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Traiter la Réclamation : ') }} <span class="text-[var(--primary-color)]">{{ Str::limit($reclamation->sujet, 50) }}</span>
            </h2>
            <a href="{{ route('reclamations.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .form-page-card { /* Main container for the whole page content inside py-12 */
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            /* shadow-xl and sm:rounded-lg are good from Breeze */
        }
        /* The html.dark selector in app.blade.php will handle variable swapping for dark mode */

        .details-section {
            margin-bottom: 2rem; /* mb-8 */
            padding: 1rem; /* p-4 */
            border: 1px solid var(--border-color-light);
            border-radius: 0.375rem; /* rounded-md */
            background-color: var(--content-bg-light); /* Slightly different from card if needed */
        }
        html.dark .details-section {
            background-color: var(--card-bg-dark); /* Match card background in dark mode */
            border-color: var(--border-color-dark);
        }
        .details-section h3 {
            font-size: 1.125rem; /* text-lg */
            font-weight: 500; /* font-medium */
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        html.dark .details-section h3 {
            color: var(--text-light);
        }
        .details-section dt { /* <dt> styling */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500;
            color: var(--text-muted-light);
        }
        html.dark .details-section dt {
            color: var(--text-muted-dark);
        }
        .details-section dd { /* <dd> styling */
            margin-top: 0.25rem; /* mt-1 */
            font-size: 0.875rem; /* text-sm */
            color: var(--text-dark);
        }
        html.dark .details-section dd {
            color: var(--text-light);
        }

        /* Form elements using custom classes to apply theme variables if needed */
        .custom-textarea, .custom-select {
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light);
            color: var(--text-dark);
            /* Tailwind classes for padding, rounding, width, etc. are already applied */
        }
        html.dark .custom-textarea, html.dark .custom-select {
            background-color: var(--card-bg-dark); /* Or var(--content-bg-dark) for darker input */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-textarea::placeholder, .custom-select::placeholder {
            color: var(--text-muted-light);
        }
        html.dark .custom-textarea::placeholder, html.dark .custom-select::placeholder {
            color: var(--text-muted-dark);
        }
        .custom-textarea:focus, .custom-select:focus {
            border-color: var(--primary-color); /* Your theme's primary color */
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); /* Assuming --primary-rgb is defined */
            outline: none;
        }

        /* Tailwind's prose dark:prose-invert should handle text colors in nl2br content for dark mode */
        /* Status badges already have Tailwind dark: variants, which is good */

        /* Alert styles for theme awareness */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Apply custom class for theme-aware card --}}
            <div class="form-page-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages') {{-- Make sure this partial is also theme aware or uses neutral styling --}}

                    {{-- Affichage des détails de la réclamation --}}
                    <div class="details-section">
                        <h3>Détails de la Réclamation</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt>Auteur</dt>
                                <dd>{{ $reclamation->auteur->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt>Date de soumission</dt>
                                <dd>{{ $reclamation->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt>Sujet</dt>
                                <dd>{{ $reclamation->sujet }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt>Description initiale</dt>
                                {{-- Tailwind's prose class will style this HTML. prose-invert handles dark mode for formatted text. --}}
                                <dd class="mt-1 prose dark:prose-invert max-w-none">{!! nl2br(e($reclamation->description)) !!}</dd>
                            </div>
                             <div class="sm:col-span-1">
                                <dt>Statut actuel</dt>
                                <dd class="mt-1">
                                     <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($reclamation->statut == 'soumise') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($reclamation->statut == 'en_cours_traitement') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200
                                        @elseif($reclamation->statut == 'resolue') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($reclamation->statut == 'rejetee') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
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
                            {{-- Assuming x-input-label already respects dark mode via its Tailwind classes --}}
                            <x-input-label for="reponse" :value="__('Votre Réponse')" />
                            <textarea id="reponse" name="reponse" rows="6"
                                      class="custom-textarea block mt-1 w-full rounded-md shadow-sm">{{ old('reponse', $reclamation->reponse) }}</textarea>
                            <x-input-error :messages="$errors->get('reponse')" class="mt-2" />
                        </div>

                        <!-- Nouveau Statut -->
                        <div>
                            <x-input-label for="statut" :value="__('Changer le Statut')" />
                            <select name="statut" id="statut" required
                                    class="custom-select block mt-1 w-full rounded-md shadow-sm">
                                @foreach ($statuts as $statut_val) {{-- $statuts is passed from controller --}}
                                    <option value="{{ $statut_val }}" {{ old('statut', $reclamation->statut) == $statut_val ? 'selected' : '' }}>
                                        {{ Str::ucfirst(str_replace('_', ' ', $statut_val)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- x-primary-button should be theme-aware from Breeze --}}
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
