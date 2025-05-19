<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-dark)] leading-tight">
            {{ __('Créer un Nouvel Utilisateur (Employé)') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        /* Re-using dashboard-like styling for form card and elements */
        .form-container-card { /* Changed from form-card to avoid potential global conflicts */
            max-width: 700px;
            margin: 2rem auto; /* Standard margin */
            background-color: var(--card-bg-light); /* Uses theme variable from app.blade.php */
            color: var(--text-dark); /* Uses theme variable */
            border-radius: 0.5rem; /* Consistent rounding */
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); /* Standard shadow */
            padding: 2rem; /* Consistent padding */
            overflow: hidden;
        }
        /* The html.dark selector from app.blade.php will handle changing --card-bg-light etc. */

        .form-container-card h1 {
            color: var(--primary-color); /* Theme primary color */
            font-size: 1.5rem; /* text-xl or text-2xl */
            font-weight: 600; /* semibold */
            text-align: center;
            margin-bottom: 1.5rem; /* mb-6 */
        }

        .form-group {
            margin-bottom: 1.25rem; /* mb-5 */
        }

        .form-group label {
            display: block;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* medium */
            color: var(--text-dark); /* Uses variable */
            margin-bottom: 0.5rem; /* mb-2 */
        }
        /* No need for .dark .form-group label if using CSS variables correctly */

        .form-control {
            border: 1px solid var(--border-color-light);
            padding: 0.65rem 1rem; /* py-2.5 px-4 */
            border-radius: 0.375rem; /* rounded-md */
            width: 100%;
            font-size: 0.9rem; /* text-sm based on most themes */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            background-color: var(--content-bg-light); /* Light input bg, for dark: var(--card-bg-dark) */
            color: var(--text-dark);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        html.dark .form-control { /* This specific override is needed if inputs should be darker than card */
            background-color: var(--sidebar-bg-dark); /* Darker input background like Breeze */
            border-color: var(--border-color-dark);
            color: var(--text-light);
        }
        .form-control::placeholder {
            color: #9CA3AF; /* gray-400 */
        }
        html.dark .form-control::placeholder {
             color: #6B7280; /* gray-500 */
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 74, 85, 162), 0.3); /* Use RGB of primary for focus ring */
            outline: none;
        }
        /* For --primary-rgb, define it in your root or just use a fixed rgba like before if primary-color is always the same HEX */
        :root {
            /* ... other vars ... */
            --primary-rgb: 74, 85, 162; /* Assuming default --primary-color: #4A55A2; */
        }
        html.dark :root {
            --primary-rgb: 129, 140, 248; /* Assuming dark mode --primary-color: #818CF8; */
        }


        .btn-submit-custom {
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: var(--text-light); /* Assuming primary buttons have light text */
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 0.375rem;
            width: 100%;
            transition: background-color 0.3s, border-color 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-submit-custom:hover {
            background-color: var(--primary-hover-color);
            border-color: var(--primary-hover-color);
        }
         /* If primary buttons in dark mode have different text color: */
        html.dark .btn-submit-custom {
             color: var(--text-dark); /* If primary buttons in dark use a light bg and dark text */
        }


        .alert-custom-danger {
            background-color: #FEF2F2; /* red-50 */
            color: #991B1B; /* red-700 */
            border: 1px solid #FECACA; /* red-300 */
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        html.dark .alert-custom-danger {
            background-color: #450A0A; /* Adjust dark mode error bg */
            color: #FCA5A5; /* Adjust dark mode error text */
            border-color: #7F1D1D; /* Adjust dark mode error border */
        }
        .alert-custom-danger strong { font-weight: 700; }
        .alert-custom-danger ul { list-style-type: disc; padding-left: 1.25rem; margin-top: 0.5rem; }

        .div-flex {
            display: grid;
            grid-template-columns: 1fr; /* Default to 1 column */
            gap: 1.25rem; /* Same as form-group margin */
        }
        @media (min-width: 640px) { /* sm breakpoint or adjust */
             .div-flex {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .form-text-muted {
            font-size: 0.8rem;
            color: var(--text-muted-light);
        }
        html.dark .form-text-muted {
             color: var(--text-muted-dark);
        }
        .link-secondary {
            color: var(--primary-color);
            text-decoration: none;
        }
        .link-secondary:hover { text-decoration: underline;}
        html.dark .link-secondary { color: var(--secondary-color); }

    </style>
    @endpush

    <div class="py-8">
        <div class="form-container-card"> {{-- Use updated class name --}}
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
                    <label for="name" class="form-label">Nom Complet :</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nom complet de l'utilisateur" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="adresse@example.com" value="{{ old('email') }}" required>
                </div>

                <div class="div-flex">
                    <div class="form-group">
                        <label for="telephone" class="form-label">Téléphone (Optionnel) :</label>
                        <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="Numéro de téléphone" value="{{ old('telephone') }}">
                    </div>

                    <div class="form-group">
                        <label for="post" class="form-label">Poste (Optionnel) :</label>
                        <input type="text" name="post" id="post" class="form-control" placeholder="Ex: Développeur, Commercial" value="{{ old('post') }}">
                    </div>
                </div>

                <div class="div-flex">
                    <div class="form-group">
                        <label for="statut" class="form-label">Statut :</label>
                        <select name="statut" id="statut" class="form-control" required>
                            {{-- Assuming $statuts is passed from controller --}}
                            @foreach ($statuts ?? ['actif' => 'Actif', 'inactif' => 'Inactif'] as $statut_val => $statut_label)
                                <option value="{{ $statut_val }}" {{ old('statut', 'actif') == $statut_val ? 'selected' : '' }}>
                                    {{ ucfirst($statut_label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="roles" class="form-label">Rôle(s) :</label>
                        <select name="roles[]" id="roles" class="form-control" multiple required>
                            {{-- Assuming $roles is passed from controller with key-value pairs --}}
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ (is_array(old('roles')) && in_array($value, old('roles'))) || (empty(old('roles')) && $label == 'employe') ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="block mt-1 form-text-muted">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs rôles.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_embauche" class="form-label">Date d'embauche (Optionnel) :</label>
                    <input type="date" name="date_embauche" id="date_embauche" class="form-control" value="{{ old('date_embauche') }}">
                </div>

                <div class="form-group mt-6">
                    <p class="form-text-muted">
                        Un mot de passe par défaut sera généré et l'utilisateur sera invité à le changer lors de sa première connexion.
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <button type="submit" class="btn-submit-custom">
                        Créer l'Utilisateur
                    </button>
                </div>
                 <div class="mt-4 text-center">
                    <a href="{{ route('users.index') }}" class="text-sm link-secondary">
                        Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
