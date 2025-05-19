<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Détails du Rôle :') }} <span class="text-[var(--primary-color)]">{{ $role->name }}</span>
            </h2>
            <a href="{{ route('roles.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour à la liste des rôles') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-content-card { /* As used in index.blade.php */
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
         html.dark .page-content-card {
             background-color: var(--card-bg-dark);
             color: var(--text-light);
        }

        .details-section-header { /* For the "Role Name" display */
            font-size: 1.5rem; /* text-2xl */
            font-weight: 600; /* semibold */
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        html.dark .details-section-header { color: var(--text-light); }

        .permissions-list-container { margin-top: 1rem; }
        .permissions-list-container strong {
            display: block; font-size: 1rem; font-weight: 500;
            margin-bottom: 0.75rem; color: var(--text-dark);
        }
        html.dark .permissions-list-container strong { color: var(--text-light); }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); /* Responsive grid for permissions */
            gap: 0.75rem;
        }
        .permission-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem; /* Adjusted padding */
            font-size: 0.8rem; /* text-xs to text-sm range */
            font-weight: 500; /* medium */
            border-radius: 9999px; /* rounded-full */
            background-color: var(--primary-color); /* Theme primary color for badges */
            color: var(--text-light); /* Text light on primary background */
            transition: background-color 0.2s ease;
        }
        .permission-badge:hover {
             background-color: var(--primary-hover-color);
        }
        html.dark .permission-badge {
            background-color: var(--secondary-color);
            color: var(--text-dark); /* Dark text on lighter secondary color */
        }
         html.dark .permission-badge:hover {
            background-color: rgba(var(--primary-rgb, 129, 140, 248), 0.7); /* Darker hover for dark secondary */
        }
        .no-permissions-text { color: var(--text-muted-light); font-style: italic; }
        html.dark .no-permissions-text { color: var(--text-muted-dark); }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="page-content-card bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="details-section-header">{{ $role->name }}</h3>
                    </div>

                    <div class="permissions-list-container">
                        <strong>Permissions Assignées :</strong>
                        @if(!empty($rolePermissions) && count($rolePermissions) > 0)
                            <div class="permissions-grid mt-2">
                                @foreach($rolePermissions as $v)
                                    <span class="permission-badge">{{ $v->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="no-permissions-text mt-2">Aucune permission assignée à ce rôle.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
