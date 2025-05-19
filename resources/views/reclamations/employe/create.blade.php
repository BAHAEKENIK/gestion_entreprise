<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Soumettre une Nouvelle Réclamation') }}
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
        .form-page-card {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
        /* Tailwind classes 'bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg' are good for the base card */

        .custom-form-element { /* Applied to select and textarea */
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light); /* Can be --card-bg-light if you prefer inputs to match card bg */
            color: var(--text-dark);
            /* Tailwind structural classes like block, mt-1, w-full, rounded-md, shadow-sm are still used on the element */
        }
        html.dark .custom-form-element {
            background-color: var(--card-bg-dark); /* For better contrast on dark theme page background */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-element::placeholder { color: var(--text-muted-light); }
        html.dark .custom-form-element::placeholder { color: var(--text-muted-dark); }

        .custom-form-element:focus {
            border-color: var(--primary-color); /* Your theme's primary color for focus */
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); /* Focus ring with primary color RGB */
            outline: none;
        }
         /* Remember to define --primary-rgb in app.blade.php if not already */

        /* Alert styling (reused and theme-aware) */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Added .form-page-card for overall theme styling --}}
            <div class="form-page-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    {{-- Ensure this partial handles its own dark mode text colors or inherits correctly --}}
                    @include('partials.flash-messages')

                    <form method="POST" action="{{ route('reclamations.store') }}" class="space-y-6">
                        @csrf

                        <!-- Destinataire (Directeur) -->
                        <div>
                            {{-- x-input-label should adapt from Breeze/Tailwind theme --}}
                            <x-input-label for="directeur_id" :value="__('Destinataire (Directeur)')" />
                            <select name="directeur_id" id="directeur_id" required
                                    class="custom-form-element block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Sélectionner un directeur --</option>
                                @foreach ($directeurs as $directeur) {{-- Assume $directeurs is passed --}}
                                    <option value="{{ $directeur->id }}" {{ old('directeur_id') == $directeur->id ? 'selected' : '' }}>
                                        {{ $directeur->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('directeur_id')" class="mt-2" />
                        </div>

                        <!-- Sujet -->
                        <div>
                            <x-input-label for="sujet" :value="__('Sujet')" />
                            {{-- x-text-input should adapt from Breeze/Tailwind theme. We add custom-form-element for extra fine-tuning if needed. --}}
                            <x-text-input id="sujet" class="custom-form-element block mt-1 w-full" type="text" name="sujet" :value="old('sujet')" required autofocus />
                            <x-input-error :messages="$errors->get('sujet')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description détaillée de la réclamation')" />
                            <textarea id="description" name="description" rows="6" required
                                      class="custom-form-element block mt-1 w-full rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- x-primary-button should adapt from Breeze/Tailwind theme --}}
                            <x-primary-button>
                                {{ __('Soumettre la Réclamation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
