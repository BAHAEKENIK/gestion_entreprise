<x-app-layout>
    <style>
        /* Container and Header Styles */
.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s ease;
}

.btn-success {
    background-color: #28a745;
    color: #fff;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-info {
    background-color: #17a2b8;
    color: #fff;
}

.btn-info:hover {
    background-color: #138496;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Alert Styles */
.alert {
    padding: 15px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    margin-bottom: 20px;
    position: relative;
}

.close-alert {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    color: #155724;
    cursor: pointer;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #333;
    color: #fff;
    font-weight: bold;
}

.table tr:hover {
    background-color: #f1f1f1;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.pagination a,
.pagination span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #007bff;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #007bff;
    color: #fff;
}

    </style>
    <div class="container">
        <div class="header">
            <h2>Role Management</h2>
            @can('role-create')
                <a class="btn btn-success" href="{{ route('roles.create') }}">
                    <span>➕</span> Create New Role
                </a>
            @endcan
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
                <button class="close-alert" onclick="this.parentElement.style.display='none';">✖</button>
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>

                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>

                        <td>{{ $role->name }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">👁 Show</a>
                            @can('role-edit')
                                <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}">✏ Edit</a>
                            @endcan
                            @can('role-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('🗑 Delete', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {!! $roles->render() !!}
        </div>
    </div>
</x-app-layout>
