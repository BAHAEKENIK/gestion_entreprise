<x-app-layout>
    <style>
        /* Container and Header Styles */
.container {
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h2 {
    font-size: 28px;
    color: #333;
    margin: 0;
}

.btn {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    background-color: #007bff;
    color: white;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

/* Details Section */
.details {
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.field {
    margin-bottom: 20px;
}

.field strong {
    display: block;
    font-size: 16px;
    color: #555;
    margin-bottom: 5px;
}

.value {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

/* Permissions Badges */
.permissions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    font-size: 14px;
    color: white;
    background-color: #eb3333;
    border-radius: 12px;
}

.badge:hover {
    background-color: #a52f31;
}

/* No Permissions */
.no-permissions {
    font-size: 14px;
    color: #888;
    font-style: italic;
}

    </style>
    <div class="container">
        <div class="header">
            <h2>Show Role</h2>
            <a class="btn btn-primary" href="{{ route('roles.index') }}">🔙 Back</a>
        </div>

        <div class="details">
            <div class="field">
                <strong>Name:</strong>
                <span class="value">{{ $role->name }}</span>
            </div>

            <div class="field">
                <strong>Permissions:</strong>
                <div class="permissions">
                    @if(!empty($rolePermissions))
                        @foreach($rolePermissions as $v)
                            <span class="badge">{{ $v->name }}</span>
                        @endforeach
                    @else
                        <span class="no-permissions">No permissions assigned</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


