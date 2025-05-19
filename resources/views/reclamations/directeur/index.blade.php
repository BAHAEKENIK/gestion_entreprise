<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Gestion des Réclamations') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-content-card { /* Consistent card styling for the page content */
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            /* shadow-xl and sm:rounded-lg are applied in the HTML below via Tailwind classes */
        }
        /* The html.dark selector in app.blade.php will handle variable swapping for dark mode */

        /* Custom styles for form elements within this page, using theme variables */
        .custom-form-select {
            border: 1px solid var(--border-color-light);
            background-color: var(--content-bg-light); /* Or card-bg-light if preferred for inputs on cards */
            color: var(--text-dark);
            /* Tailwind classes already handle: block, mt-1, w-full, rounded-md, shadow-sm, text-sm */
        }
        html.dark .custom-form-select {
            background-color: var(--content-bg-dark); /* For consistency, use content's dark BG for inputs */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .custom-form-select:focus {
            border-color: var(--primary-color); /* Your theme's primary color */
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.3); /* Focus ring using primary color RGB */
            outline: none;
        }
        /* :root and html.dark :root in app.blade.php should define --primary-rgb */

        .custom-primary-button { /* If you needed to customize x-primary-button beyond Tailwind */
            background-color: var(--primary-color);
            /* other properties like text color (likely --text-light), padding, etc. */
        }
        html.dark .custom-primary-button {
             /* Adjust if dark mode primary button needs different styles */
        }

        /* Table styles: Use Tailwind dark: variants for simplicity and consistency with Breeze. */
        /* If you need custom table theming like in other examples, add those styles here. */
        /* Example: */
        /* .reclamations-table th { color: var(--text-muted-light); } */
        /* html.dark .reclamations-table th { color: var(--text-muted-dark); } */
        /* .reclamations-table td { border-bottom: 1px solid var(--border-color-light); } */
        /* html.dark .reclamations-table td { border-bottom: 1px solid var(--border-color-dark); } */

        /* Alert custom styling (reused from previous) */
        .alert-container { margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .alert-success { background-color: #D1FAE5; border-color: #6EE7B7; color: #065F46; }
        html.dark .alert-success { background-color: #064E3B; border-color: #34D399; color: #A7F3D0; }
        .alert-danger { background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
        html.dark .alert-danger { background-color: #450A0A; border-color: #B91C1C; color: #FECACA; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Added page-content-card for themed background and default text color --}}
            <div class="page-content-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages') {{-- Assuming this partial also respects dark mode or uses generic Tailwind --}}

                    <form method="GET" action="{{ route('reclamations.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                {{-- If x-input-label isn't theme-aware, use custom class or inline style with var() --}}
                                <x-input-label for="statut_filter" :value="__('Filtrer par Statut')" class="text-[var(--text-dark)]" />
                                {{-- Applying custom-form-select, keeping existing Tailwind for structure --}}
                                <select name="statut_filter" id="statut_filter"
                                        class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les statuts</option>
                                    @foreach ($statuts as $statut) {{-- Assuming $statuts is passed from controller --}}
                                        <option value="{{ $statut }}" {{ request('statut_filter') == $statut ? 'selected' : '' }}>
                                            {{ Str::ucfirst(str_replace('_', ' ', $statut)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <x-input-label for="employe_filter" :value="__('Filtrer par Employé')" class="text-[var(--text-dark)]"/>
                                <select name="employe_filter" id="employe_filter"
                                        class="custom-form-select block mt-1 w-full rounded-md shadow-sm text-sm">
                                    <option value="">Tous les employés</option>
                                    @foreach ($employes as $employe) {{-- Assuming $employes is passed --}}
                                        <option value="{{ $employe->id }}" {{ request('employe_filter') == $employe->id ? 'selected' : '' }}>
                                            {{ $employe->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- x-primary-button should already be theme-aware from Breeze --}}
                            <x-primary-button type="submit" class="h-10 text-sm">
                                {{ __('Filtrer') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if($reclamations->count() > 0)
                        <div class="overflow-x-auto">
                            {{-- If Tailwind's default table dark mode is good, no need for .custom-styled-table class --}}
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sujet</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Auteur (Employé)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Soumission</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reclamations as $reclamation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ Str::limit($reclamation->sujet, 40) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reclamation->auteur->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                 <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($reclamation->statut == 'soumise') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                    @elseif($reclamation->statut == 'en_cours_traitement') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200
                                                    @elseif($reclamation->statut == 'resolue') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                    @elseif($reclamation->statut == 'rejetee') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                                    @endif">
                                                    {{ Str::ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reclamation->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                {{-- Action links using Tailwind theme colors --}}
                                                <a href="{{ route('reclamations.show', $reclamation) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Voir
                                                </a>
                                                @if($reclamation->statut == 'soumise' || $reclamation->statut == 'en_cours_traitement')
                                                @can('reclamation-traiter-directeur')
                                                <a href="{{ route('reclamations.edit', $reclamation) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Traiter
                                                </a>
                                                @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $reclamations->appends(request()->query())->links() }}
                        </div>
                    @else
                        <p class="text-gray-700 dark:text-gray-300">Aucune réclamation à traiter ou correspondant à vos filtres.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
