@section('styles')
<style>
    body {
        background-image: url('{{ asset('images/Site-UITS-a-propo.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        margin: 0;
        backdrop-filter: brightness(0.5); /* assombrit */
    }

    /* Theme Toggle Button */
    #theme-toggle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: #007bff;
        border: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        cursor: pointer;
        transition: all 0.4s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    #theme-toggle.clicked {
        background-color: #ff6347;
        transform: translateX(80px);
    }

    #theme-toggle:hover {
        opacity: 0.9;
    }

    body.light-theme {
        backdrop-filter: brightness(1);
    }

    body.dark-theme {
        backdrop-filter: brightness(0.5);
    }
</style>
@endsection

<x-app-layout>
    <button id="theme-toggle"></button>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
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
    
</x-app-layout>
