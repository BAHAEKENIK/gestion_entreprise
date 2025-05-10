<x-app-layout>
    <style>
        /* General Container Styles */
        .form-container { /* Renamed from .container to avoid conflict if Breeze uses .container */
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
            border: 1px solid #e9ecef;
        }

        /* Header Section */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .page-header h2 {
            font-size: 26px;
            color: #343a40;
            margin-bottom: 0; /* Handled by flex alignment */
        }

        /* Buttons General */
        .btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500; /* medium */
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, transform 0.1s ease;
            display: inline-block; /* Ensures padding and margin work correctly */
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0069d9;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group strong {
            display: block;
            font-size: 14px;
            font-weight: 600; /* semi-bold */
            margin-bottom: 8px;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        /* Permissions Section */
        .permissions-group {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .permissions-group strong { /* "Permission:" heading */
             margin-bottom: 15px;
        }

        .permission-item label {
            display: flex; /* Aligns checkbox and text nicely */
            align-items: center;
            font-size: 15px;
            font-weight: 400;
            margin-bottom: 12px;
            color: #343a40;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .permission-item label:hover {
            background-color: #e9ecef;
        }

        .permission-item input[type="checkbox"].name {
            margin-right: 10px;
            width: 18px; /* Custom size for checkbox */
            height: 18px;
            accent-color: #007bff; /* Modern way to color checkbox control */
            cursor: pointer;
        }
        .permission-item input[type="checkbox"].name:focus {
            outline: 2px solid #80bdff;
            outline-offset: 1px;
        }


        /* Error Messages */
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .alert-danger strong {
            font-weight: bold;
        }

        .alert-danger ul {
            margin-top: 10px;
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-danger li {
            font-size: 14px;
        }

        /* Submit Button Area */
        .submit-button-container {
            text-align: center;
            margin-top: 20px; /* Add some space above submit button */
        }


        /* Footer Note */
        .footer-note {
            margin-top: 30px;
            font-size: 13px;
            color: #6c757d;
            text-align: center;
        }
        .footer-note small a {
            color: #007bff;
            text-decoration: none;
        }
        .footer-note small a:hover {
            text-decoration: underline;
        }

    </style>

    <!-- Utilisation du conteneur stylisé -->
    <div class="form-container">

        <!-- En-tête de page modifié -->
        <div class="page-header">
            <h2>Create New Role</h2>
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <!-- Groupe de permissions stylisé -->
                    <div class="permissions-group">
                        <strong>Permission:</strong>
                        {{-- Removed the <br/> before the loop --}}
                        @foreach($permission as $value)
                            <!-- Item de permission pour un meilleur style de label/checkbox -->
                            <div class="permission-item">
                                <label>
                                    {{ Form::checkbox('permission[]', $value->name, false, array('class' => 'name')) }}
                                    {{ $value->name }}
                                </label>
                            </div>
                            {{-- Removed the <br/> after each label --}}
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <!-- Conteneur pour le bouton submit pour un meilleur contrôle du style -->
                <div class="submit-button-container">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

        <!-- Pied de page stylisé -->
        <p class="footer-note"><small>Tutorial by <a href="https://itsolutionstuff.com" target="_blank" rel="noopener noreferrer">ItSolutionStuff.com</a></small></p>
    </div>
</x-app-layout>
