<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Tâches') }}
            </h2>
            <a href="{{ route('taches.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('Nouvelle Tâche') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @include('partials.flash-messages') {{-- Créez ce partial pour les messages success/error --}}

                    <form method="GET" action="{{ route('taches.directeur.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="statut_filter" :value="__('Statut')" />
                                <select name="statut_filter" id="statut_filter" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Tous les statuts</option>
                                    @foreach ($statuts as $statut)
                                        <option value="{{ $statut }}" {{ request('statut_filter') == $statut ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="employe_id_filter" :value="__('Employé')" />
                                <select name="employe_id_filter" id="employe_id_filter" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Tous les employés</option>
                                    @foreach ($employes as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employe_id_filter') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-primary-button class="mt-7">
                                    {{ __('Filtrer') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    @if($taches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Fin Prévue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taches as $tache)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('taches.show', $tache) }}" class="text-indigo-600 hover:text-indigo-900">{{ Str::limit($tache->titre, 30) }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tache->employeAssignee->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($tache->statut == 'terminee') bg-green-100 text-green-800 @elseif($tache->statut == 'en_cours' || $tache->statut == 'en_revision') bg-yellow-100 text-yellow-800 @elseif($tache->statut == 'annulee') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tache->date_fin_prevue ? $tache->date_fin_prevue->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('taches.directeur.edit', $tache) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Modifier</a>
                                            <form action="{{ route('taches.destroy', $tache) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $taches->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <p>Aucune tâche trouvée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
