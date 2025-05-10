@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md relative" role="alert">
        <strong class="font-bold">Succès!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md relative" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md relative" role="alert">
        <strong class="font-bold">Attention!</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-md relative" role="alert">
        <strong class="font-bold">Info:</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md relative" role="alert">
        <strong class="font-bold">Erreurs de validation:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
