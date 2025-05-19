<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
                {{ __('Modifier le Rôle : ') }} {{ $role->name }}
            </h2>
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* Reusing styles from roles/create.blade.php for consistency */
        .form-card-container {
            max-width: 700px; margin: 2rem auto; padding: 2rem;
            background-color: var(--card-bg-light); color: var(--text-dark);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color-light);
        }
        html.dark .form-card-container { background-color: var(--card-bg-dark); color: var(--text-light); border-color: var(--border-color-dark); }

        .form-card-container h1 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-dark); text-align:center; }
        html.dark .form-card-container h1 { color: var(--text-light); }

        .form-group { margin-bottom: 1.25rem; }
        .form-group strong, .form-group .form-label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-dark); }
        html.dark .form-group strong, html.dark .form-group .form-label { color: var(--text-light); }

        .form-control {
            width: 100%; padding: 0.65rem 1rem; font-size: 0.9rem; line-height: 1.5;
            color: var(--text-dark); background-color: var(--content-bg-light);
            border: 1px solid var(--border-color-light); border-radius: 0.375rem; box-sizing: border-box;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        html.dark .form-control { background-color: var(--card-bg-dark); border-color: var(--border-color-dark); color: var(--text-light); }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); outline: none; }

        .permissions-group { background-color: var(--content-bg-light); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color-light); }
        html.dark .permissions-group { background-color: var(--card-bg-dark); border-color:var(--border-color-dark) }
        .permissions-group strong { margin-bottom: 1rem; font-size: 1rem; }

        .permission-item label { display: flex; align-items: center; font-size: 0.9rem; font-weight: 400; margin-bottom: 0.75rem; color: var(--text-dark); cursor: pointer; padding: 0.5rem 0.75rem; border-radius: 0.25rem; transition: background-color 0.2s ease; }
        html.dark .permission-item label { color: var(--text-light); }
        .permission-item label:hover { background-color: var(--sidebar-active-bg-light); }
        html.dark .permission-item label:hover { background-color: var(--sidebar-active-bg-dark); }
        .permission-item input[type="checkbox"].name { margin-right: 0.625rem; width: 1.1em; height: 1.1em; accent-color: var(--primary-color); cursor: pointer; border-radius: 0.25rem; border: 1px solid var(--border-color-light); }
        html.dark .permission-item input[type="checkbox"].name { border-color: var(--border-color-dark); }
        .permission-item input[type="checkbox"].name:focus { box-shadow: 0 0 0 2px rgba(var(--primary-rgb),0.2); outline:none;}

        .btn-submit-custom { background-color: var(--primary-color); border-color: var(--primary-color); color: var(--text-light); padding: 0.65rem 1.5rem; font-size: 0.9rem; font-weight: 600; border-radius: 0.375rem; width: auto; transition: background-color 0.3s; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
        .btn-submit-custom:hover { background-color: var(--primary-hover-color); border-color: var(--primary-hover-color); }
        html.dark .btn-submit-custom { color: var(--text-dark); background-color: var(--secondary-color); border-color:var(--secondary-color)}

        .alert-custom-danger { background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; }
        html.dark .alert-custom-danger { background-color: var(--card-bg-dark); color: #FCA5A5; border-color: #B91C1C; }
        .alert-custom-danger strong { font-weight: 700; }
        .alert-custom-danger ul { list-style-type: disc; padding-left: 1.25rem; margin-top: 0.5rem; }

        .btn-back-custom { /* Custom style for back button if Breeze one doesn't fit */
            background-color: var(--text-muted-light); color: var(--text-dark); padding: 0.5rem 1rem; text-decoration: none; border-radius:0.375rem; font-size:0.8rem;
        }
        html.dark .btn-back-custom { background-color:var(--text-muted-dark); color:var(--text-light); }
        .btn-back-custom:hover { opacity:0.8; }

    </style>
    @endpush

    <div class="py-12">
        <div class="form-card-container"> {{-- Consistent container --}}
             {{-- Header title already handled by x-slot --}}
            <div class="flex justify-end mb-6">
                <a href="{{ route('roles.index') }}" class="btn-back-custom inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>

            @if (count($errors) > 0)
                <div class="alert-custom-danger">
                    <strong>Oups!</strong> Des erreurs sont survenues :<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id], 'class' => 'space-y-6']) !!}
            <div>
                <div class="form-group">
                    <strong class="form-label">Nom du Rôle :</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Nom', 'class' => 'form-control', 'id' => 'name')) !!}
                </div>
            </div>
            <div>
                <div class="form-group">
                    <div class="permissions-group">
                        <strong class="form-label">Permissions :</strong>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-2 mt-2">
                            @foreach($permission as $value)
                                <div class="permission-item">
                                    <label>
                                        {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                        <span>{{ $value->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8">
                <button type="submit" class="btn-submit-custom">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</x-app-layout>
