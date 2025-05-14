<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion des Réclamations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    @include('partials.flash-messages')

                    <form method="GET" action="{{ route('reclamations.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="statut_filter" :value="__('Filtrer par Statut')" />
                                <select name="statut_filter" id="statut_filter" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <option value="">Tous les statuts</option>
                                    @foreach ($statuts as $statut)
                                        <option value="{{ $statut }}" {{ request('statut_filter') == $statut ? 'selected' : '' }}>
                                            {{ Str::ucfirst(str_replace('_', ' ', $statut)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <x-input-label for="employe_filter" :value="__('Filtrer par Employé')" />
                                <select name="employe_filter" id="employe_filter" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <option value="">Tous les employés</option>
                                    @foreach ($employes as $employe)
                                        <option value="{{ $employe->id }}" {{ request('employe_filter') == $employe->id ? 'selected' : '' }}>
                                            {{ $employe->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button type="submit" class="h-10 text-sm">
                                {{ __('Filtrer') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if($reclamations->count() > 0)
                        <div class="overflow-x-auto">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($reclamation->sujet, 40) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reclamation->auteur->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                 <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($reclamation->statut == 'soumise') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                    @elseif($reclamation->statut == 'en_cours_traitement') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                                    @elseif($reclamation->statut == 'resolue') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($reclamation->statut == 'rejetee') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @endif">
                                                    {{ Str::ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reclamation->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
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
