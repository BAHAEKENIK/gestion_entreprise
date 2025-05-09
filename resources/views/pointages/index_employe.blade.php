<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Pointage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pointage du Jour</h3>

                    @if ($pointageActif)
                        <p class="mb-2">Vous avez pointé votre arrivée à : <span class="font-semibold">{{ $pointageActif->pointe_debut->format('H:i:s') }}</span> le {{ $pointageActif->pointe_debut->format('d/m/Y') }}.</p>
                        @if($pointageActif->description)
                        <p class="text-sm text-gray-600 mb-2">Description arrivée: {{ $pointageActif->description }}</p>
                        @endif
                        <form method="POST" action="{{ route('pointages.depart') }}">
                            @csrf
                            @method('PATCH')
                             <div class="mt-4">
                                <x-input-label for="description_depart" :value="__('Description pour le départ (optionnel)')" />
                                <x-text-input id="description_depart" class="block mt-1 w-full" type="text" name="description_depart" />
                            </div>
                            <x-primary-button class="mt-4">
                                {{ __('Pointer Départ') }}
                            </x-primary-button>
                        </form>
                    @else
                        @php
                            // Vérifier si un pointage complet existe déjà pour aujourd'hui
                            $pointageCompletAujourdhui = \App\Models\Pointage::where('employe_id', Auth::id())
                                ->whereDate('pointe_debut', \Carbon\Carbon::today())
                                ->whereNotNull('pointe_fin')
                                ->exists();
                        @endphp
                        @if($pointageCompletAujourdhui)
                             <p class="mb-4 p-3 bg-blue-100 text-blue-700 rounded">Vous avez déjà complété votre pointage pour aujourd'hui.</p>
                        @else
                            <p class="mb-2">Vous n'avez pas encore pointé votre arrivée aujourd'hui.</p>
                            <form method="POST" action="{{ route('pointages.arrivee') }}">
                                @csrf
                                <div class="mt-4">
                                    <x-input-label for="description_arrivee" :value="__('Description pour l\'arrivée (optionnel)')" />
                                    <x-text-input id="description_arrivee" class="block mt-1 w-full" type="text" name="description_arrivee" />
                                </div>
                                <x-primary-button class="mt-4">
                                    {{ __('Pointer Arrivée') }}
                                </x-primary-button>
                            </form>
                        @endif
                    @endif

                    <hr class="my-6">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Historique de mes Pointages</h3>
                    @if($historiquePointages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($historiquePointages as $pointage)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pointage->pointe_debut->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pointage->pointe_debut->format('H:i:s') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $pointage->pointe_fin ? $pointage->pointe_fin->format('H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($pointage->description, 50) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $historiquePointages->links() }}
                        </div>
                    @else
                        <p>Aucun historique de pointage trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
