<x-app-layout>
    <style>
     .details-container {
    font-family: 'Arial', sans-serif;
    padding: 20px;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.fade-in {
    opacity: 0;
    animation: fadeIn 1s ease forwards;
}

.slide-in {
    transform: translateY(20px);
    opacity: 0;
    animation: slideIn 1s ease forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-header {
    background: #C2185B;
    color: #fff;
    padding: 10px 20px;
    font-size: 18px;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.btn-secondary {
    background: #D32F2F;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
}

.btn-secondary:hover {
    background: #5a6268;
}

   </style>
    <div class="details-container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Afficher l'utilisateur</h2>
                </div>

            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <strong>Détails de l'utilisateur</strong>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <strong>Nom :</strong>
                    {{ $user->name }}
                </div>
                <div class="form-group">
                    <strong>Email :</strong>
                    {{ $user->email }}
                </div>
                <div class="form-group">
                    <strong>Téléphone :</strong>
                    {{ $user->tele }}
                </div>

                <div class="form-group">
                    <strong>Rôles :</strong>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $role)
                            <label class="badge badge-success">{{ $role }}</label>
                        @endforeach
                    @endif
                </div>
                <div class="pull-right">
                    <a class="btn btn-secondary"  href="{{ route('users.index') }}"> Retour</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const fadeElements = document.querySelectorAll('.fade-in, .slide-in');
    fadeElements.forEach((el, index) => {
        setTimeout(() => {
            el.style.animationDelay = `${index * 0.2}s`;
        }, 100);
    });
});

    </script>
</x-app-layout>
