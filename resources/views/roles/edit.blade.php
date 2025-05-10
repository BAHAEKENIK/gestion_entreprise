<style>
    /* === General Styles === */
    /* body { */ /* Normalement géré par app.blade.php et Tailwind */
        /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5; /* Un gris plus clair et moderne */
        /* margin: 0;
        padding: 0;
        color: #333;
    } */

    /* === Container for this specific page === */
    /* Si vous voulez un conteneur spécifique en dehors du slot principal de Breeze */
    .role-edit-container { /* Vous ajouteriez cette classe au div principal de votre contenu */
        max-width: 700px; /* Gardé */
        margin: 40px auto;
        padding: 25px 30px; /* Augmenté pour plus d'aération */
        background-color: #ffffff;
        border-radius: 12px; /* Plus arrondi */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08); /* Ombre plus douce */
        border: 1px solid #e5e7eb; /* Bordure subtile */
    }

    /* === Header Styles === */
    .role-edit-header h2 { /* Sélecteur plus spécifique */
        font-size: 1.75rem; /* Un peu plus grand */
        font-weight: 600; /* semi-bold */
        margin-bottom: 25px;
        color: #1f2937; /* Gris foncé de Tailwind */
        /* text-align: center; */ /* Optionnel si vous préférez centré */
    }

    .role-edit-actions .btn-back { /* Classe spécifique pour le bouton retour */
        text-decoration: none;
        padding: 0.6rem 1rem; /* Ajusté */
        border-radius: 0.375rem; /* rounded-md */
        font-size: 0.875rem; /* text-sm */
        font-weight: 500; /* medium */
        background-color: #6b7280; /* Gris Tailwind */
        color: white;
        transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
    }
    .role-edit-actions .btn-back:hover {
        background-color: #4b5563; /* Gris plus foncé */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .role-edit-actions .btn-back svg { /* Si vous ajoutez une icône SVG */
        margin-right: 0.5em;
        width: 1em;
        height: 1em;
    }


    /* === Form Styles === */
    .role-edit-form .form-group { /* Sélecteur plus spécifique */
        margin-bottom: 1.5rem; /* Espacement accru */
    }

    .role-edit-form .form-group strong,
    .role-edit-form .form-group label.main-label { /* Pour les labels principaux des champs */
        display: block;
        font-size: 0.9rem; /* Un peu plus petit, plus moderne */
        margin-bottom: 0.5rem; /* 8px */
        color: #4b5563; /* Gris moyen */
        font-weight: 500; /* medium */
    }

    .role-edit-form .form-control { /* Style pour les inputs générés par laravelcollective */
        width: 100%;
        padding: 0.75rem 1rem; /* Espacement interne accru */
        font-size: 1rem; /* Taille de police standard */
        border: 1px solid #d1d5db; /* Gris clair Tailwind */
        border-radius: 0.375rem; /* rounded-md */
        box-sizing: border-box;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #fff;
        color: #1f2937;
    }
    .role-edit-form .form-control:focus {
        border-color: #c34a64; /* Votre couleur rose pour le focus */
        box-shadow: 0 0 0 3px rgba(195, 74, 100, 0.25); /* Similaire à ring de Tailwind */
        outline: none;
    }

    /* === Permissions List specific styling === */
    .role-edit-form .permissions-group label { /* Pour les labels des checkboxes de permission */
        font-size: 0.95rem; /* Ajusté */
        color: #374151; /* Gris foncé */
        display: inline-flex; /* Pour aligner checkbox et texte */
        align-items: center;
        margin-right: 1.5rem; /* Espace entre les permissions */
        margin-bottom: 0.5rem; /* Espace vertical */
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }
    .role-edit-form .permissions-group label:hover {
        color: #c34a64; /* Votre rose au survol */
    }
    .role-edit-form .permissions-group input[type="checkbox"].name { /* Cible la checkbox */
        margin-right: 0.5rem; /* Espace entre checkbox et texte du label */
        width: 1.15em; /* Taille de la checkbox */
        height: 1.15em;
        border-radius: 0.25rem;
        border-color: #d1d5db;
        color: #c34a64; /* Couleur de la coche */
        transition: box-shadow 0.2s ease-in-out;
    }
    .role-edit-form .permissions-group input[type="checkbox"].name:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(195, 74, 100, 0.25); /* Effet de focus */
    }
    .role-edit-form .permissions-group input[type="checkbox"].name:checked {
        background-color: #c34a64; /* Fond quand coché */
        border-color: #c34a64;
    }
    .role-edit-form .permissions-group br { /* Cache les <br/> si on utilise flex/grid pour les permissions */
        /* display: none; */ /* Décommentez si vous mettez les permissions en grille */
    }
    /* Optionnel: Mettre les permissions en grille */
    /* .permissions-list-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.5rem;
    } */


    /* === Error Messages === */
    .role-edit-alert-danger { /* Sélecteur plus spécifique */
        background-color: #fef2f2; /* Rouge clair Tailwind */
        color: #991b1b; /* Rouge foncé Tailwind */
        border: 1px solid #fecaca; /* Bordure rouge Tailwind */
        padding: 1rem; /* Espacement interne */
        border-radius: 0.5rem; /* Plus arrondi */
        margin-bottom: 1.5rem;
    }
    .role-edit-alert-danger strong {
        font-weight: 600; /* semibold */
    }
    .role-edit-alert-danger ul {
        margin-top: 0.5rem; /* Espacement avant la liste */
        padding-left: 1.25rem; /* Indentation de la liste */
        list-style-type: disc;
    }
    .role-edit-alert-danger li {
        font-size: 0.875rem; /* text-sm */
    }

    /* === Submit Button === */
    .role-edit-form button[type="submit"].btn-submit-custom { /* Sélecteur plus spécifique */
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600; /* semibold */
        color: white;
        background-color: #c34a64; /* Votre rose */
        border: none;
        border-radius: 0.375rem; /* rounded-md */
        cursor: pointer;
        transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        width: auto; /* S'adapte au contenu, ou width: 100% si vous voulez pleine largeur */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .role-edit-form button[type="submit"].btn-submit-custom:hover {
        background-color: #a11143; /* Rose plus foncé */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .role-edit-form button[type="submit"].btn-submit-custom svg { /* Si vous ajoutez une icône SVG */
        margin-right: 0.5em;
        width: 1.1em;
        height: 1.1em;
    }
    .text-center-custom { /* Pour centrer le bouton */
        text-align: center;
        margin-top: 1.5rem; /* mt-6 */
    }

</style>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modifier le Rôle : ') }} {{ $role->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        {{-- Utilisation du conteneur global de Breeze pour la largeur max et le padding --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Enveloppez votre formulaire dans un conteneur avec un fond blanc pour le mode clair / gris pour mode sombre --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 role-edit-container">
                {{-- Le titre est déjà dans le slot header de Breeze, mais si vous voulez un titre spécifique au formulaire : --}}
                {{-- <div class="role-edit-header">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Modifier le Rôle</h2>
                </div> --}}

                <div class="flex justify-end mb-6">
                    <a class="role-edit-actions btn-back inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" href="{{ route('roles.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour
                    </a>
                </div>


                @if (count($errors) > 0)
                    <div class="role-edit-alert-danger">
                        <strong>Oups!</strong> Des erreurs sont survenues :<br><br>
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Application de la classe role-edit-form au formulaire --}}
                {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id], 'class' => 'role-edit-form space-y-6']) !!}

                <div> {{-- Enveloppe chaque groupe de formulaire pour un meilleur espacement si besoin --}}
                    <div class="form-group">
                        <label for="name" class="main-label"><strong>Nom du Rôle :</strong></label>
                        {!! Form::text('name', null, array('placeholder' => 'Nom du rôle', 'class' => 'form-control', 'id' => 'name')) !!}
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label class="main-label"><strong>Permissions :</strong></label>
                        <div class="mt-2 space-y-2 permissions-group"> {{-- Optionnel: permissions-list-grid pour une grille --}}
                            @foreach($permission as $value)
                                <label class="inline-flex items-center">
                                    {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800')) }}
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $value->name }}</span>
                                </label>
                                <br class="sm:hidden"/> {{-- Saut de ligne pour mobile, si pas en grille --}}
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center-custom mt-8">
                    <button type="submit" class="btn-submit-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Mettre à jour
                    </button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</x-app-layout>
