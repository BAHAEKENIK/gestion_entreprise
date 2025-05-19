<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Historique des Pointages de : ') }} <span class="text-[var(--primary-color)]">{{ $user->name }}</span>
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .page-card-container {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
        }
        /* Dark mode handled by variables from app.blade.php */

        .pointages-table thead { background-color: var(--sidebar-bg-light); }
        html.dark .pointages-table thead { background-color: var(--sidebar-bg-dark); }
        .pointages-table th { color: var(--text-muted-light); }
        html.dark .pointages-table th { color: var(--text-muted-dark); }
        .pointages-table tbody { background-color: var(--card-bg-light); }
        html.dark .pointages-table tbody { background-color: var(--card-bg-dark); }
        .pointages-table td { border-bottom: 1px solid var(--border-color-light); color: var(--text-dark); }
        html.dark .pointages-table td { border-bottom-color: var(--border-color-dark); color: var(--text-light); }
        .text-link-themed { color: var(--primary-color); }
        html.dark .text-link-themed { color: var(--secondary-color); }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="page-card-container bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="mb-4">
                        <a href="{{ route('pointages.index') }}" class="text-link-themed hover:underline text-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Retour au suivi général
                        </a>
                    </div>

                    @if($historiquePointages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="pointages-table min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead {{-- class="bg-gray-50 dark:bg-gray-700" --}}>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Arrivée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Départ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody {{-- class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" --}}>
                                    @foreach ($historiquePointages as $pointage)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $pointage->pointe_debut->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pointage->pointe_debut->format('H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $pointage->pointe_fin ? $pointage->pointe_fin->format('H:i:s') : '-' }}
                                        </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm">{{ Str::limit($pointage->description, 50) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $historiquePointages->links() }}
                        </div>
                    @else
                        <p class="text-[var(--text-muted-light)]">Aucun historique de pointage trouvé pour cet employé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
