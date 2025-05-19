<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="{{ Auth::check() && Auth::user()->theme ? Auth::user()->theme : 'light' }}"
      x-data="{
          theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
          init() {
              if (this.theme === 'dark') {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
              localStorage.setItem('theme', this.theme);
              window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: this.theme }})); // For other components to listen
          },
          toggleTheme() {
              this.theme = (this.theme === 'light') ? 'dark' : 'light';
              this.init();
          }
      }"
      x-init="init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Entreprise') }} - @yield('title', 'Dashboard')</title>

    <link rel="icon" type="image/png" sizes="1000x1000" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #4A55A2; /* Default primary - similar to your 'easy' project */
            --primary-hover-color: #3A4382;
            --secondary-color: #7895CB; /* Default secondary */

            --sidebar-bg-light: #F9FAFB; /* gray-50 */
            --sidebar-text-light: #374151; /* gray-700 */
            --sidebar-icon-light: #6B7280; /* gray-500 */
            --sidebar-active-bg-light: theme('colors.indigo.100'); /* Adjust if not using Tailwind vars */
            --sidebar-active-text-light: var(--primary-color);
            --sidebar-active-icon-light: var(--primary-color);
            --sidebar-hover-bg-light: #EFF6FF; /* blue-50 or similar */
            --sidebar-hover-text-light: var(--primary-color);


            --sidebar-bg-dark: #1F2937; /* gray-800 */
            --sidebar-text-dark: #D1D5DB; /* gray-300 */
            --sidebar-icon-dark: #9CA3AF; /* gray-400 */
            --sidebar-active-bg-dark: #374151; /* gray-700 */
            --sidebar-active-text-dark: #FFFFFF;
            --sidebar-active-icon-dark: #FFFFFF;
            --sidebar-hover-bg-dark: #374151;
            --sidebar-hover-text-dark: #FFFFFF;

            --topbar-bg-light: #FFFFFF;
            --topbar-bg-dark: #1F2937; /* gray-800 */
            --topbar-text-light: #374151;
            --topbar-text-dark: #D1D5DB;
            --topbar-border-light: #E5E7EB; /* gray-200 */
            --topbar-border-dark: #374151; /* gray-700 */

            --content-bg-light: #F3F4F6; /* gray-100 */
            --content-bg-dark: #111827;  /* gray-900 */
            --card-bg-light: #FFFFFF;
            --card-bg-dark: #1F2937;
            --text-light: #F9FAFB;
            --text-dark: #111827;
            --border-color-light: #E5E7EB;
            --border-color-dark: #374151;
        }

        /* Apply dark mode variables if html has .dark class */
        html.dark {
            --primary-color: #818CF8; /* Indigo-400 for dark theme */
            --primary-hover-color: #6366F1; /* Indigo-500 */
            --secondary-color: #A78BFA; /* Violet-400 for dark */

            --sidebar-bg-light: var(--sidebar-bg-dark);
            --sidebar-text-light: var(--sidebar-text-dark);
            --sidebar-icon-light: var(--sidebar-icon-dark);
            --sidebar-active-bg-light: var(--sidebar-active-bg-dark);
            --sidebar-active-text-light: var(--sidebar-active-text-dark);
            --sidebar-active-icon-light: var(--sidebar-active-icon-dark);
            --sidebar-hover-bg-light: var(--sidebar-hover-bg-dark);
            --sidebar-hover-text-light: var(--sidebar-hover-text-dark);

            --topbar-bg-light: var(--topbar-bg-dark);
            --topbar-text-light: var(--topbar-text-dark);
            --topbar-border-light: var(--topbar-border-dark);

            --content-bg-light: var(--content-bg-dark);
            --card-bg-light: var(--card-bg-dark);
            --text-dark: var(--text-light); /* Flip default text */
            --border-color-light: var(--border-color-dark);
        }


        body {
            background-color: var(--content-bg-light);
            color: var(--text-dark); /* Set default text color */
            transition: background-color 0.3s, color 0.3s;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 260px 1fr; /* Sidebar width and main content */
            min-height: 100vh;
        }

        .sidebar {
            width: 260px; /* Fixed width */
            background-color: var(--sidebar-bg-light);
            border-right: 1px solid var(--border-color-light);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: -260px; /* Hidden by default on mobile */
            height: 100vh;
            z-index: 40;
            transition: transform 0.3s ease-in-out, background-color 0.3s, border-color 0.3s;
            overflow-y: auto;
        }
        .sidebar.open {
            transform: translateX(260px);
        }
        @media (min-width: 1024px) { /* lg breakpoint */
            .sidebar {
                transform: translateX(0); /* Always visible on larger screens */
                position:sticky; /* Can be sticky within the grid cell */
            }
            .main-content-area {
                 /* margin-left: 260px; Removed as grid handles layout */
            }
             #mobileMenuButton { display:none; }
        }


        .sidebar-header { padding: 1.5rem; text-align: center; border-bottom: 1px solid var(--border-color-light); }
        .sidebar-logo { height: 40px; display:inline-block;}

        .sidebar-nav { flex-grow: 1; padding: 1rem 0; }
        .sidebar-nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav-item a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0.5rem; /* Added horizontal margin */
            border-radius: 0.375rem;
            color: var(--sidebar-text-light);
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar-nav-item a:hover {
            background-color: var(--sidebar-hover-bg-light);
            color: var(--sidebar-hover-text-light);
        }
        .sidebar-nav-item a.active { /* For active link */
            background-color: var(--sidebar-active-bg-light);
            color: var(--sidebar-active-text-light);
            font-weight: 600;
        }
        .sidebar-nav-item a.active i { color: var(--sidebar-active-icon-light); }
        .sidebar-nav-item i {
            margin-right: 0.75rem; width: 20px; text-align: center;
            font-size: 1.1rem; color: var(--sidebar-icon-light);
            transition: color 0.2s;
        }
        .sidebar-nav-item a:hover i { color: var(--sidebar-hover-text-light); } /* Ensures icon matches text on hover */


        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color-light);
        }
        .user-profile-sidebar { display: flex; align-items: center; }
        .user-profile-sidebar img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem;}
        .user-profile-sidebar .user-name { font-weight: 500; color: var(--text-dark); font-size:0.9rem; }
        html.dark .user-profile-sidebar .user-name { color: var(--text-light); }
        .user-profile-sidebar .user-role { font-size: 0.75rem; color: var(--text-muted-light); }
        html.dark .user-profile-sidebar .user-role { color: var(--text-muted-dark); }

        .main-content-area {
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: var(--content-bg-light);
        }
        @media (min-width: 1024px) {
             .main-content-area { /* This takes up the remaining space in the grid */ }
        }


        /* Top bar styles from layouts.navigation but adapted for the new structure */
        .top-bar {
            background-color: var(--topbar-bg-light);
            border-bottom: 1px solid var(--topbar-border-light);
            /* box-shadow: 0 1px 3px rgba(0,0,0,0.05); */
        }
        .top-bar-content {
            display: flex;
            justify-content: space-between; /* Pushes hamburger to left, actions to right */
            align-items: center;
            height: 4rem; /* 64px */
            padding: 0 1rem; /* px-4 */
        }
         @media (min-width: 640px) { .top-bar-content { padding: 0 1.5rem; } } /* sm:px-6 */
         @media (min-width: 1024px) { .top-bar-content { padding: 0 2rem; justify-content: flex-end; /* On large screens, only actions are on the right */ } } /* lg:px-8 */

        .top-bar-actions { display: flex; align-items: center; gap: 0.75rem; /* space-x-3 */ }
        #theme-toggle-btn, .notification-btn {
            padding: 0.5rem; /* p-2 */
            border-radius: 9999px; /* rounded-full */
            color: var(--topbar-text-light);
            background-color: transparent;
            border:none; cursor:pointer;
            transition: background-color 0.2s, color 0.2s;
        }
        #theme-toggle-btn:hover, .notification-btn:hover {
            background-color: var(--sidebar-hover-bg-light); /* Reusing sidebar hover for consistency */
            color: var(--sidebar-hover-text-light);
        }
        #theme-toggle-btn i, .notification-btn i { font-size: 1.25rem; }

        /* Page Header (optional, if used via $header slot) */
        .page-header-container { background-color: var(--card-bg-light); box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .page-header-content { max-width: 7xl; margin-left:auto; margin-right:auto; padding: 1.5rem 1rem; }
        @media (min-width: 640px) { .page-header-content { padding: 1.5rem 1.5rem; } }
        @media (min-width: 1024px) { .page-header-content { padding: 1.5rem 2rem; } }


        /* Dropdown component styling from Breeze for user menu */
        .dropdown-content { background-color: var(--card-bg-light); }
        html.dark .dropdown-content { background-color: var(--card-bg-dark); }

    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar" id="mainSidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Entreprise" class="sidebar-logo">
                </a>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="sidebar-nav-item">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <i class="fas fa-tachometer-alt"></i>{{ __('Dashboard') }}
                        </x-nav-link>
                    </li>

                    @hasrole('employe')
                        <li class="sidebar-nav-item">
                            <x-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index')">
                                <i class="fas fa-user-clock"></i>{{ __('Mon Pointage') }}
                            </x-nav-link>
                        </li>
                        <li class="sidebar-nav-item">
                            <x-nav-link :href="route('taches.employe.index')" :active="request()->routeIs('taches.employe.index') || request()->routeIs('taches.show') || request()->routeIs('taches.realiser.form')">
                                <i class="fas fa-tasks"></i>{{ __('Mes Tâches') }}
                            </x-nav-link>
                        </li>
                        <li class="sidebar-nav-item">
                            <x-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.create') || request()->routeIs('reclamations.show')">
                                <i class="fas fa-exclamation-circle"></i>{{ __('Mes Réclamations') }}
                            </x-nav-link>
                        </li>
                    @endhasrole

                    @hasrole('directeur')
                        <li class="sidebar-nav-item">
                            @php
                                $nouvellesReclamationsCountDirecteur = \App\Models\Reclamation::where('directeur_id', Auth::id())
                                                                ->whereIn('statut', ['soumise'])
                                                                ->count();
                            @endphp
                            <x-nav-link :href="route('reclamations.index')" :active="request()->routeIs('reclamations.index') || request()->routeIs('reclamations.show') || request()->routeIs('reclamations.edit')" class="relative">
                                <i class="fas fa-file-alt"></i>{{ __('Réclamations') }}
                                @if($nouvellesReclamationsCountDirecteur > 0)
                                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[var(--sidebar-bg-light)] dark:border-[var(--sidebar-bg-dark)]" title="{{ $nouvellesReclamationsCountDirecteur }} new"></span>
                                @endif
                            </x-nav-link>
                        </li>
                        <li class="sidebar-nav-item">
                            <x-nav-link :href="route('pointages.index')" :active="request()->routeIs('pointages.index') || request()->routeIs('pointages.historique.employe')">
                                 <i class="fas fa-history"></i>{{ __('Suivi Pointages') }}
                            </x-nav-link>
                        </li>
                        <li class="sidebar-nav-item">
                             <x-nav-link :href="route('taches.directeur.index')" :active="request()->routeIs('taches.directeur.index') || request()->routeIs('taches.create') || request()->routeIs('taches.directeur.edit') || request()->routeIs('taches.show')">
                                <i class="fas fa-clipboard-check"></i>{{ __('Gestion Tâches') }}
                            </x-nav-link>
                        </li>
                        <li class="sidebar-nav-item">
                            <x-nav-link :href="route('users.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'users.')">
                                <i class="fas fa-users-cog"></i>{{ __('Utilisateurs') }}
                            </x-nav-link>
                        </li>
                         <li class="sidebar-nav-item">
                            <x-nav-link :href="route('roles.index')" :active="\Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'roles.')">
                                <i class="fas fa-user-tag"></i>{{ __('Rôles') }}
                            </x-nav-link>
                        </li>
                    @endhasrole
                </ul>
            </nav>

            <div class="sidebar-footer">
                 <a href="{{ route('profile.edit') }}" class="user-profile-sidebar block hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-md">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=FFFFFF&background=4A55A2' }}" alt="{{ Auth::user()->name }}">
                    <div>
                        <p class="user-name">{{ Auth::user()->name }}</p>
                        <p class="user-role capitalize">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-content-area">
            <!-- Top Bar -->
            <header class="top-bar sticky top-0 z-30">
                <div class="top-bar-content">
                     <!-- Hamburger Menu Button for Mobile -->
                    <button id="mobileMenuButton" class="lg:hidden p-2 text-[var(--topbar-text-light)] hover:text-[var(--primary-color)]">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="top-bar-actions">
                        <button id="theme-toggle-btn" title="Toggle theme" @click="toggleTheme()">
                             <i class="fas fa-sun" x-show="theme === 'light'"></i>
                             <i class="fas fa-moon" x-show="theme === 'dark'" style="display:none;"></i>
                        </button>

                        {{-- Notifications placeholder --}}
                        <button class="notification-btn" title="Notifications">
                            <i class="fas fa-bell"></i>
                            {{-- <span class="absolute top-0 right-0 block h-2 w-2 transform translate-x-1/2 -translate-y-1/2 rounded-full ring-2 ring-white bg-red-500"></span> --}}
                        </button>

                        <!-- Settings Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                            <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[var(--topbar-text-light)] bg-transparent hover:text-[var(--primary-color)] focus:outline-none transition ease-in-out duration-150">
                                <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=FFFFFF&background=4A55A2' }}" alt="{{ Auth::user()->name }}" />
                                <div class="hidden md:block">{{ Str::words(Auth::user()->name, 1, '') }}</div>
                                <div class="ml-1 hidden md:block">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-[var(--card-bg-light)] ring-1 ring-black ring-opacity-5 dropdown-content"
                                 style="display: none;">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="page-header-container">
                    <div class="page-header-content">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow p-4 sm:p-6 lg:p-8">
                {{ $slot ?? '' }}
                @hasSection('content')
                    @yield('content')
                @endif
            </main>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('mainSidebar');

            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    // Optional: Add overlay to body when sidebar is open on mobile
                    if (sidebar.classList.contains('open')) {
                        document.body.classList.add('sidebar-open-overlay'); // You'd need CSS for this overlay
                    } else {
                        document.body.classList.remove('sidebar-open-overlay');
                    }
                });
            }

            // For Alpine theme toggle (from html tag) to sync icons
            const themeToggleButtonDesktop = document.getElementById('theme-toggle-btn');
            const sunIconDesktop = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>';
            const moonIconDesktop = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>';

            function updateThemeIconDesktop(currentTheme) {
                if (themeToggleButtonDesktop) {
                    themeToggleButtonDesktop.innerHTML = currentTheme === 'dark' ? moonIconDesktop : sunIconDesktop;
                }
            }
            // Initial icon based on Alpine's theme
            if(window.Alpine && window.Alpine.store && window.Alpine.store('theme')) {
                 updateThemeIconDesktop(window.Alpine.store('theme').current);
            } else {
                 updateThemeIconDesktop(localStorage.getItem('theme') || 'light');
            }

            window.addEventListener('theme-changed', (event) => {
                updateThemeIconDesktop(event.detail.theme);
            });

             // If your app.js from Vite includes Alpine, this might conflict or need adjustment.
             // If using Blade-only Alpine like here, it's fine.
        });
    </script>
    @stack('scripts')
</body>
</html>
