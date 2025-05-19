<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        /* Container for each section card */
        .profile-section-card {
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            /* shadow-sm sm:rounded-lg are Tailwind classes, should already be good */
        }
        /* html.dark .profile-section-card handled by app.blade.php's html.dark :root variables */

        /* Modal content theming - this is crucial */
        /* Assuming x-modal component renders a div with a class like 'bg-white dark:bg-gray-800' */
        /* We can override this or ensure it uses variables if the component allows custom classes */
        /* For more direct control on the modal itself: */
        div[x-show="show"] > div.max-w-xl { /* This targets the modal panel inside x-modal, a bit specific */
            background-color: var(--card-bg-light) !important; /* Force our card background */
            color: var(--text-dark) !important; /* Force our text color */
            border: 1px solid var(--border-color-light);
        }
        html.dark div[x-show="show"] > div.max-w-xl {
            background-color: var(--card-bg-dark) !important; /* Modal background in dark mode */
            color: var(--text-light) !important;             /* Modal text in dark mode */
            border: 1px solid var(--border-color-dark);
        }

        /* If x-input-label needs specific theming for profile page beyond Breeze defaults */
        .profile-form .form-label {
            color: var(--text-muted-light);
        }
        html.dark .profile-form .form-label {
            color: var(--text-muted-dark);
        }

        /* If x-text-input needs specific theming for profile page */
        .profile-form .form-input {
            border-color: var(--border-color-light);
            background-color: var(--content-bg-light); /* or var(--card-bg-light) if inputs on card */
            color: var(--text-dark);
        }
        html.dark .profile-form .form-input {
            border-color: var(--border-color-dark);
            background-color: var(--card-bg-dark); /* Match card dark for inputs */
            color: var(--text-light);
        }
        .profile-form .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); /* Ensure --primary-rgb is set in app.blade.php */
            outline:none;
        }
        /* Custom button styling (Breeze x-primary-button and x-danger-button should be mostly theme-aware) */
        /* But if you need to ensure specific colors from your variables: */
        .profile-form .custom-primary-btn {
            background-color: var(--primary-color); color: var(--text-light); /* Adjust if your primary has dark text */
        }
        .profile-form .custom-primary-btn:hover { background-color: var(--primary-hover-color); }
        html.dark .profile-form .custom-primary-btn { background-color: var(--secondary-color); color: var(--text-dark); } /* Example */

        .profile-form .custom-danger-btn {
            background-color: #EF4444; /* red-500 */ color: white;
        }
        .profile-form .custom-danger-btn:hover { background-color: #DC2626; /* red-600 */ }
        html.dark .profile-form .custom-danger-btn {
             background-color: #F87171; /* red-400 */ color: var(--text-dark); /* Or white depending on contrast */
        }
         html.dark .profile-form .custom-danger-btn:hover { background-color: #EF4444; }

         /* Styling for SweetAlert to respect theme - example */
        .swal2-popup.dark-theme-swal {
            background: var(--card-bg-dark) !important;
            color: var(--text-light) !important;
        }
        .swal2-title.dark-theme-swal { color: var(--text-light) !important; }
        .swal2-html-container.dark-theme-swal { color: var(--text-light) !important; }
        /* You'd apply 'dark-theme-swal' via customClass dynamically in JS */
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Applied custom class for theming card background and text color --}}
            <div class="p-4 sm:p-8 profile-section-card bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-section-card bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-section-card bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDarkModeActive = () => document.documentElement.classList.contains('dark');

            const swalThemeProps = () => ({
                customClass: {
                    popup: isDarkModeActive() ? 'bg-gray-800 text-gray-200' : 'bg-white',
                    // confirmButton: '...', // Add classes if you need to style Swal buttons
                    // cancelButton: '...',
                }
            });

            @if (session('status') === 'profile-updated')
                Swal.fire({ icon: 'success', title: 'Profil Mis à Jour!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, ...swalThemeProps() });
            @elseif (session('status') === 'password-updated')
                 Swal.fire({ icon: 'success', title: 'Mot de Passe Mis à Jour!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, ...swalThemeProps() });
            @elseif (session('status') === 'theme-updated') // Assuming you have this session status from ProfileController
                 Swal.fire({ icon: 'success', title: 'Thème Mis à Jour!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true, ...swalThemeProps() });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
