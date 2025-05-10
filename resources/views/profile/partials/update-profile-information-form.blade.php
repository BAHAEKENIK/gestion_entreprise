{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Champ Téléphone (modifiable) -->
        <div>
            <x-input-label for="telephone" :value="__('Telephone')" />
            <x-text-input id="telephone" name="telephone" type="tel" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <!-- Champ Poste (modifiable par le directeur, non par l'utilisateur ici) -->
        @if($user->post)
        <div>
            <x-input-label for="post_display" :value="__('Poste')" />
            <x-text-input id="post_display" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 cursor-not-allowed" :value="$user->post" disabled />
        </div>
        @endif

        <!-- Champ Date d'embauche (non modifiable) -->
        <div>
            <x-input-label for="date_embauche_display" :value="__('Date d\'embauche')" />
            <x-text-input
                id="date_embauche_display"
                type="text"
                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 cursor-not-allowed"
                :value="$user->date_embauche ? \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y') : __('N/A')"
                disabled
            />
        </div>

        <!-- Champ Rôle (non modifiable ici) -->
        <div>
            <x-input-label for="role_display" :value="__('Rôle(s)')" />
            <x-text-input
                id="role_display"
                type="text"
                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 cursor-not-allowed"
                :value="$user->getRoleNames()->isNotEmpty() ? $user->getRoleNames()->implode(', ') : __('N/A')"
                disabled
            />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
