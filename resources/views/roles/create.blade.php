<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Créer Nouveau Rôle') }}
            </h2>
            <a href="{{ route('roles.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:border-gray-700 dark:focus:border-gray-600 focus:ring ring-gray-300 dark:focus:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .form-card-container { /* Standardized card container class */
            max-width: 700px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: var(--card-bg-light);
            color: var(--text-dark);
            border-radius: 0.75rem; /* sm:rounded-lg */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); /* shadow-xl */
            border: 1px solid var(--border-color-light); /* Optional border */
        }
        html.dark .form-card-container {
            background-color: var(--card-bg-dark);
            color: var(--text-light);
            border-color: var(--border-color-dark);
        }

        .form-card-container h1 { /* Using h1 for consistency if this replaces x-slot header */
            font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;
            color: var(--text-dark); text-align:center;
        }
        html.dark .form-card-container h1 { color: var(--text-light); }


        .form-group { margin-bottom: 1.25rem; }
        .form-group strong, .form-group .form-label { /* For labels */
            display: block; font-size: 0.875rem; font-weight: 500;
            margin-bottom: 0.5rem; color: var(--text-dark);
        }
        html.dark .form-group strong, html.dark .form-group .form-label { color: var(--text-light); }

        .form-control { /* For laravelcollective Form inputs */
            width: 100%; padding: 0.65rem 1rem; font-size: 0.9rem;
            line-height: 1.5; color: var(--text-dark);
            background-color: var(--content-bg-light); /* Can be same as card for flatter look */
            border: 1px solid var(--border-color-light);
            border-radius: 0.375rem; box-sizing: border-box;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        html.dark .form-control {
            background-color: var(--card-bg-dark); /* Slightly lighter than pure content-bg for inputs */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3);
            outline: none;
        }

        .permissions-group {
            background-color: var(--content-bg-light); /* Slightly offset from card bg */
            padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color-light);
        }
        html.dark .permissions-group { background-color: var(--card-bg-dark); border-color:var(--border-color-dark) }
        .permissions-group strong { margin-bottom: 1rem; font-size: 1rem; }

        .permission-item label {
            display: flex; align-items: center; font-size: 0.9rem; font-weight: 400;
            margin-bottom: 0.75rem; color: var(--text-dark); cursor: pointer;
            padding: 0.5rem 0.75rem; border-radius: 0.25rem; transition: background-color 0.2s ease;
        }
        html.dark .permission-item label { color: var(--text-light); }
        .permission-item label:hover { background-color: var(--sidebar-active-bg-light); /* Reusing sidebar hover */ }
        html.dark .permission-item label:hover { background-color: var(--sidebar-active-bg-dark); }
        .permission-item input[type="checkbox"].name { /* For Form::checkbox generated input */
            margin-right: 0.625rem; width: 1.1em; height: 1.1em;
            accent-color: var(--primary-color); cursor: pointer;
            border-radius: 0.25rem; border: 1px solid var(--border-color-light); /* For browsers not supporting accent-color */
        }
        html.dark .permission-item input[type="checkbox"].name { border-color: var(--border-color-dark); }
        .permission-item input[type="checkbox"].name:focus { box-shadow: 0 0 0 2px rgba(var(--primary-rgb),0.2); outline:none;}


        .btn-submit-custom {
            background-color: var(--primary-color); border-color: var(--primary-color);
            color: var(--text-light); padding: 0.65rem 1.5rem; font-size: 0.9rem;
            font-weight: 600; border-radius: 0.375rem; width: 100%;
            transition: background-color 0.3s; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-submit-custom:hover { background-color: var(--primary-hover-color); border-color: var(--primary-hover-color); }
        html.dark .btn-submit-custom { color: var(--text-dark); background-color: var(--secondary-color); border-color:var(--secondary-color)}


        /* Alert styling (from create.blade.php) */
        .alert-custom-danger { background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; }
        html.dark .alert-custom-danger { background-color: var(--card-bg-dark); color: #FCA5A5; border-color: #B91C1C; }
        .alert-custom-danger strong { font-weight: 700; }
        .alert-custom-danger ul { list-style-type: disc; padding-left: 1.25rem; margin-top: 0.5rem; }

    </style>
    @endpush

    <div class="py-12">
        <div class="form-card-container"> {{-- Using consistent container --}}
            <h1>{{ __('Créer un Nouveau Rôle') }}</h1>

            @if (count($errors) > 0)
                <div class="alert-custom-danger">
                    <strong>Oups !</strong> Des erreurs sont survenues :<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
            <div class="space-y-6"> {{-- Adding Tailwind space-y for consistent spacing between form groups --}}
                <div class="form-group">
                    <strong class="form-label">Nom du Rôle :</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Nom', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    <div class="permissions-group">
                        <strong class="form-label">Permissions :</strong>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-2 mt-2">
                            @foreach($permission as $value)
                                <div class="permission-item">
                                    <label>
                                        {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                        <span>{{ $value->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-submit-custom">
                        <i class="fas fa-save mr-2"></i>{{ __('Soumettre') }}
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</x-app-layout>
