{{-- resources/views/partials/flash-messages.blade.php
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 dark:bg-green-700/20 dark:text-green-300 dark:border-green-600 rounded-md relative" role="alert">
        <strong class="font-bold">Succès!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-700/20 dark:text-red-300 dark:border-red-600 rounded-md relative" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 dark:bg-yellow-700/20 dark:text-yellow-300 dark:border-yellow-600 rounded-md relative" role="alert">
        <strong class="font-bold">Attention!</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 dark:bg-blue-700/20 dark:text-blue-300 dark:border-blue-600 rounded-md relative" role="alert">
        <strong class="font-bold">Info:</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
    </div>
@endif --}}

{{-- Pour les erreurs de validation d'importation spécifiques --}}
{{-- @if ($errors->any() && session('import_form_error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-700/20 dark:text-red-300 dark:border-red-600 rounded-md relative" role="alert">
        <strong class="font-bold">Erreurs de validation:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
