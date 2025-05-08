<x-app-layout>
    <style>
         .btnn {
            background-color: #C2185B;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
            justify-content: center !important;
    display: flex;
    position: absolute;
        }
        .form-control {
            width: 100px !important;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px 0 0 20px;
            font-size: 16px;
            outline: none;
            position: relative;


        }

        .form-control:focus {
            border-color: #D32F2F;
        }
        .input-group {
            display: flex;
            justify-content: center;
        }
        button.btnn {
    text-align: center;
    margin-left: 455px !important;
    height: 47px;
    font-size: 20px;
}

    </style>
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" action="{{ route('users.index') }}" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    <button type="submit" class="btnn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <a class="btn btn-success" href="{{ route('users.create') }}">
                    <i class="fas fa-user-plus"></i> Create New User
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Administrative Officer</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered text-center custom-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <span class="badge bg-success">{{ $v }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>

                                    <a class="btn btn-warning btn-sm" href="{{ route('users.edit', $user->id) }}" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline', 'id' => 'delete-form-'.$user->id]) !!}
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <style>
        .custom-table th, .custom-table td {
            font-size: 1.1rem; /* Increase font size */
            padding: 12px; /* Add padding for better readability */
        }
        .custom-table th {
            width: 25%; /* Adjust column width */
        }
        .custom-table td {
            width: 25%; /* Adjust column width */
        }
    </style>
</x-app-layout>
