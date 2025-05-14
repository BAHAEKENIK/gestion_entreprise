<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        {{-- Hauteur du logo mise à h-10 (40px) comme demandé --}}
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Entreprise" class="block h-5 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @hasrole('employe')
                        <x-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index')">
                            {{ __('Mon Pointage') }}
                        </x-nav-link>
                        <x-nav-link :href="route('taches.employe.index')" :active="request()->routeIs('taches.employe.index') || request()->routeIs('taches.show') || request()->routeIs('taches.realiser.form')">
                            {{ __('Mes Tâches') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.create') || request()->routeIs('reclamations.show')">
                            {{ __('Mes Réclamations') }}
                        </x-nav-link>
                    @endhasrole

                    @hasrole('directeur')
                        @php
                            $nouvellesReclamationsCountDirecteur = \App\Models\Reclamation::where('directeur_id', Auth::id())
                                                            ->whereIn('statut', ['soumise'])
                                                            ->count();
                        @endphp
                        <x-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.show') || request()->routeIs('reclamations.edit')">
                            {{ __('Réclamations') }}
                            @if($nouvellesReclamationsCountDirecteur > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                    {{ $nouvellesReclamationsCountDirecteur }}
                                </span>
                            @endif
                        </x-nav-link>
                        <x-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index') || request()->routeIs('pointages.historique.employe')">
                            {{ __('Suivi Pointages') }}
                        </x-nav-link>
                        <x-nav-link :href="route('taches.directeur.index')" :active="request()->routeIs('taches.directeur.index') || request()->routeIs('taches.create') || request()->routeIs('taches.directeur.edit') || request()->routeIs('taches.show')">
                            {{ __('Gestion Tâches') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'users.')">
                            {{ __('Utilisateurs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('roles.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'roles.')">
                            {{ __('Rôles') }}
                        </x-nav-link>
                    @endhasrole
                </div>
            </div>

            <!-- Settings Dropdown & Theme Toggle -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                 <!-- Theme Toggle Button - Déplacé ici pour une meilleure visibilité à côté du dropdown utilisateur -->
                

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                 <!-- Theme Toggle Button pour mobile (optionnel, si vous voulez un look différent) -->
                 <button id="theme-toggle-button-mobile" title="Changer de thème" class="mr-2 p-2 rounded-full text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg id="theme-toggle-icon-mobile" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        {{-- L'icône sera injectée par JavaScript --}}
                    </svg>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @hasrole('employe')
                <x-responsive-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index')">
                    {{ __('Mon Pointage') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('taches.employe.index')" :active="request()->routeIs('taches.employe.index') || request()->routeIs('taches.show') || request()->routeIs('taches.realiser.form')">
                    {{ __('Mes Tâches') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.create') || request()->routeIs('reclamations.show')">
                    {{ __('Mes Réclamations') }}
                </x-responsive-nav-link>
            @endhasrole

            @hasrole('directeur')
                @php
                    $nouvellesReclamationsCountDirecteurResponsive = \App\Models\Reclamation::where('directeur_id', Auth::id())
                                                    ->whereIn('statut', ['soumise'])
                                                    ->count();
                @endphp
                <x-responsive-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.show') || request()->routeIs('reclamations.edit')">
                    <div class="flex justify-between items-center w-full">
                        <span>{{ __('Réclamations') }}</span>
                        @if($nouvellesReclamationsCountDirecteurResponsive > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $nouvellesReclamationsCountDirecteurResponsive }}
                            </span>
                        @endif
                    </div>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index') || request()->routeIs('pointages.historique.employe')">
                    {{ __('Suivi Pointages') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('taches.directeur.index')" :active="request()->routeIs('taches.directeur.index') || request()->routeIs('taches.create') || request()->routeIs('taches.directeur.edit') || request()->routeIs('taches.show')">
                    {{ __('Gestion Tâches') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'users.')">
                    {{ __('Utilisateurs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('roles.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'roles.')">
                    {{ __('Rôles') }}
                </x-responsive-nav-link>
            @endhasrole
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
