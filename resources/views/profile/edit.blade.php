<x-app-layout>
    {{-- Le bouton de thème est maintenant dans layouts.app.blade.php --}}
    {{-- <button id="theme-toggle"></button> --}} {{-- Supprimez cette ligne si elle y était --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Inclure le formulaire de mise à jour des informations du profil --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    {{-- Le script SweetAlert peut rester ici ou être déplacé dans layouts.app.blade.php si utilisé globalement --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Profil Mis à Jour!',
                    toast: true, // Pour un affichage moins intrusif
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: { popup: 'dark:bg-gray-800 dark:text-gray-200' }
                });
            @elseif (session('status') === 'password-updated')
                 Swal.fire({
                    icon: 'success',
                    title: 'Mot de Passe Mis à Jour!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: { popup: 'dark:bg-gray-800 dark:text-gray-200' }
                });
            @elseif (session('status') === 'theme-updated')
                 Swal.fire({
                    icon: 'success',
                    title: 'Thème Mis à Jour!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000, // Plus court pour le thème
                    timerProgressBar: true,
                    customClass: { popup: 'dark:bg-gray-800 dark:text-gray-200' }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
