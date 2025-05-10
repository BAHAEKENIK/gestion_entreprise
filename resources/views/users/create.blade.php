<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un Nouvel Utilisateur (Employé)') }}
        </h2>
    </x-slot>

    {{-- CSS Personnalisé pour ce formulaire --}}
    <style>
        .form-card {
            max-width: 700px; /* Augmenté pour plus d'espace */
            margin: 30px auto;
            background: #f9fafb; /* Couleur de fond plus neutre, s'adapte au mode sombre de Breeze */
            /* background: linear-gradient(178deg, #ff8080, #fdfcfc, #ff0e0e, #d53070); */ /* Commenté pour utiliser bg-white/dark:bg-gray-800 */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px 30px;
            overflow: hidden;
        }

        .form-card h1 {
            color: #c34a64; /* Votre couleur rose */
            font-size: 1.75rem; /* Ajusté */
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 1.25rem; /* 20px */
        }

        .form-group label {
            display: block;
            font-size: 0.9rem; /* Ajusté */
            font-weight: 600; /* Tailwind: font-semibold */
            color: #374151; /* Tailwind: text-gray-700 */
            margin-bottom: 0.5rem; /* 8px */
        }
        .dark .form-group label { /* Style pour le mode sombre */
            color: #d1d5db; /* Tailwind: dark:text-gray-300 */
        }

        .form-control { /* Ces styles seront en conflit avec Tailwind. Préférez les classes Tailwind si possible. */
            border: 1px solid #d1d5db; /* Tailwind: border-gray-300 */
            padding: 0.75rem 1rem; /* 12px 16px */
            border-radius: 0.375rem; /* Tailwind: rounded-md */
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Tailwind: shadow-sm */
            background-color: #fff;
        }
        .dark .form-control { /* Mode sombre pour les inputs */
            background-color: #374151; /* Tailwind: dark:bg-gray-700 */
            border-color: #4b5563; /* Tailwind: dark:border-gray-600 */
            color: #f3f4f6; /* Tailwind: dark:text-gray-100 */
        }
        .form-control:focus {
            border-color: #c34a64; /* Votre rose */
            box-shadow: 0 0 0 3px rgba(195, 74, 100, 0.3); /* Similaire à ring de Tailwind */
            outline: none;
        }
        .dark .form-control:focus {
            border-color: #c34a64;
            box-shadow: 0 0 0 3px rgba(195, 74, 100, 0.4);
        }

        .btn-submit-custom { /* Classe personnalisée pour le bouton pour éviter les conflits */
            background-color: #c34a64;
            border-color: #c34a64;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.375rem;
            width: 100%;
            transition: background-color 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit-custom:hover {
            background-color: #D32F2F;
            border-color: #D32F2F;
        }

        .alert-custom-danger { /* Classe personnalisée pour les alertes */
            background-color: #fef2f2; /* Tailwind: bg-red-50 */
            color: #991b1b; /* Tailwind: text-red-700 */
            border: 1px solid #fecaca; /* Tailwind: border-red-300 */
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        .alert-custom-danger strong {
            font-weight: 700; /* Tailwind: font-bold */
        }
        .alert-custom-danger ul {
            list-style-type: disc;
            padding-left: 1.25rem; /* Tailwind: pl-5 */
            margin-top: 0.5rem;
        }

        .div-flex { /* Pour les champs sur la même ligne */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive */
            gap: 1rem; /* 16px */
        }
    </style>

    <div class="py-8">
        <div class="form-card animated-form bg-white dark:bg-gray-800">
            <h1>Créer un Nouvel Utilisateur</h1>

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

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Nom Complet :</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nom complet de l'utilisateur" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="adresse@example.com" value="{{ old('email') }}" required>
                </div>

                <div class="div-flex">
                    <div class="form-group">
                        <label for="telephone">Téléphone (Optionnel) :</label>
                        <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="Numéro de téléphone" value="{{ old('telephone') }}">
                    </div>

                    <div class="form-group">
                        <label for="post">Poste (Optionnel) :</label>
                        <input type="text" name="post" id="post" class="form-control" placeholder="Ex: Développeur, Commercial" value="{{ old('post') }}">
                    </div>
                </div>

                <div class="div-flex">
                    <div class="form-group">
                        <label for="statut">Statut :</label>
                        <select name="statut" id="statut" class="form-control" required>
                            @foreach ($statuts as $statut_val)
                                <option value="{{ $statut_val }}" {{ old('statut', 'actif') == $statut_val ? 'selected' : '' }}>
                                    {{ ucfirst($statut_val) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="roles">Rôle(s) :</label>
                        {{-- Si vous permettez de sélectionner plusieurs rôles, gardez 'multiple'.
                             Sinon, enlevez 'multiple' et changez 'roles[]' en 'roles'.
                             Pour créer un employé, on peut forcer le rôle 'employe' dans le contrôleur
                             ou le présélectionner ici et le rendre caché/désactivé si on ne veut pas que le directeur le change.
                             Pour l'instant, je laisse la sélection multiple si d'autres rôles "employés" existent.
                        --}}
                        <select name="roles[]" id="roles" class="form-control" multiple required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ (is_array(old('roles')) && in_array($value, old('roles'))) || (empty(old('roles')) && $value == 'employe') ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-1 text-xs text-gray-500 dark:text-gray-400">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs rôles.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_embauche">Date d'embauche (Optionnel) :</label>
                    <input type="date" name="date_embauche" id="date_embauche" class="form-control" value="{{ old('date_embauche') }}">
                </div>

                <div class="form-group mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Un mot de passe par défaut sera généré et l'utilisateur sera invité à le changer lors de sa première connexion.
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <button type="submit" class="btn-submit-custom">
                        Créer l'Utilisateur
                    </button>
                </div>
                 <div class="mt-4 text-center">
                    <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
