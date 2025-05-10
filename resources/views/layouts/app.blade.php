<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ Auth::check() ? Auth::user()->theme : 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Entreprise') }}</title>

    <!-- Favicon & Fonts -->
    <link rel="icon" type="image/png" sizes="1000x1000" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- App Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles spécifiques au thème -->
    @push('styles')
    <style>
        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            transition: backdrop-filter 0.5s, background-image 0.5s;
        }

        html.light body {
            background-image: url('{{ asset('images/Site-UITS-a-propo.png') }}');
            backdrop-filter: brightness(1);
        }

        html.dark body {
            background-image: url('{{ asset('images/votre-image-sombre.png') }}');
            backdrop-filter: brightness(0.5);
        }

        #theme-toggle-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 0.5rem;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(0,0,0,0.1);
            cursor: pointer;
            transition: background-color 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #theme-toggle-button:hover {
            background-color: rgba(255, 255, 255, 0.7);
        }

        html.dark #theme-toggle-button {
            background-color: rgba(50, 50, 50, 0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }

        html.dark #theme-toggle-button:hover {
            background-color: rgba(50, 50, 50, 0.7);
        }

        #theme-toggle-icon {
            width: 24px;
            height: 24px;
            color: #333;
        }

        html.dark #theme-toggle-icon {
            color: #fff;
        }
    </style>
    @endpush

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Bouton de changement de thème -->
        <button id="theme-toggle-button" title="Changer de thème">
            <svg id="theme-toggle-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"></svg>
        </button>

        <!-- En-tête de page -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Contenu principal -->
        <main>
            {{ $slot }}
        </main>

        @hasSection('content')
        <div>
            @yield('content')
        </div>
        @endif
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    <script>
        const themeToggleButton = document.getElementById('theme-toggle-button');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');
        const htmlElement = document.documentElement;

        const icons = {
            sun: `<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />`,
            moon: `<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />`
        };

        function applyTheme(theme) {
            htmlElement.classList.toggle('dark', theme === 'dark');
            htmlElement.classList.toggle('light', theme === 'light');
            themeToggleIcon.innerHTML = theme === 'dark' ? icons.sun : icons.moon;
        }

        // Initialiser le thème
        applyTheme(htmlElement.classList.contains('dark') ? 'dark' : 'light');

        themeToggleButton.addEventListener('click', () => {
            const current = htmlElement.classList.contains('dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);

            fetch('{{ route("profile.update.theme") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ theme: next })
            })
            .then(res => {
                if (!res.ok) {
                    console.error('Échec de la mise à jour du thème sur le serveur');
                    applyTheme(current);
                }
            })
            .catch(err => {
                console.error('Erreur de mise à jour du thème :', err);
                applyTheme(current);
            });
        });
    </script>
</body>
</html>
