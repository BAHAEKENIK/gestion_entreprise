<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3 d-flex justify-content-between align-items-center">
                <h2 class="text-danger">Modifier User</h2>
                <a href="{{ route('users.index') }}" class="btn btn-danger text-light">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Il y a eu quelques problèmes avec votre saisie.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="background: linear-gradient(135deg, #f60404, #000000);">
            <div class="card-body">
                {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="name" class="text-light"><strong>Nom Complet:</strong></label>
                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="email" class="text-light"><strong>Email:</strong></label>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="roles" class="text-light"><strong>Role:</strong></label>
                            {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control', 'multiple']) !!}
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save"></i> Soumettre
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    timer: 3000
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    timer: 3000
                });
            @endif
        });
    </script>
</x-app-layout>
