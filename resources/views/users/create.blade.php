<style>


    h1 {
        color: #c34a64;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 20px;
    }


    .form-group label {
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .form-control {
        border: 2px solid #c34a64;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #D32F2F;
        box-shadow: 0 0 5px rgba(211, 47, 47, 0.5);
    }

    .form-control::placeholder {
        color: #888;
    }

    .btn-primary {
        background-color: #c34a64;
        border-color: #c34a64;
        color: #fff;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 5px;
        width: 100%;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #D32F2F;
        border-color: #D32F2F;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert-danger ul {
        list-style-type: none;
        padding: 0;
    }

    .alert-danger li {
        margin: 5px 0;
    }

    select.form-control {
        height: 40px;
    }

    textarea.form-control {
        height: 150px;
        resize: vertical;
    }
    .div-flex{
        display: flex;
        gap: 67px;
    }
    #status{
        position: absolute;
width: auto;
margin-top: 20px !important;
}



/* Global Style */
body {
font-family: Arial, sans-serif;
background-color: #f5f5f5;
margin: 0;
padding: 0;
}

/* Card Container */
.form-card {
max-width: 600px;
margin: 50px auto;
background: #cedce7;
background: -webkit-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
background: -o-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
background: linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
background: linear-gradient(178deg, #ff8080, #fdfcfc, #ff0e0e, #d53070);
border-radius: 12px;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
padding: 20px;
overflow: hidden;
position: relative;
transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form-card:hover {
transform: translateY(-5px);
box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

/* Form Group */
.form-group {
margin-bottom: 20px;
}

label {
display: block;
margin-bottom: 8px;
font-weight: bold;
color: #333;
}

input,
textarea,
select {
width: 100%;
padding: 10px;
border: 1px solid #ddd;
border-radius: 8px;
font-size: 16px;
box-sizing: border-box;
}

textarea {
resize: vertical;
}

/* Button */
.btn-primary {
background-color: #e63946;
color: #fff;
padding: 10px 20px;
border: none;
border-radius: 8px;
cursor: pointer;
transition: background-color 0.3s ease;
}

.btn-primary:hover {
background-color: #d62839;
}

/* Flex Container */
.div-flex {
display: flex;
gap: 15px;
}

/* Animation */
.animated-form {
animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
form{
opacity: 0;
transform: translateY(20px);
}
to {
opacity: 1;
transform: translateY(0);
}
}



</style>
<div class="row">

    <div class="col-lg-12 margin-tb">

        <div class="pull-left">

            <h2>Create New User</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>

        </div>

    </div>

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




{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Name:</strong>

            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Email:</strong>

            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}

        </div>

    </div>

    {{-- <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Password:</strong>

            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}

        </div>

    </div> --}}

    {{-- <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Confirm Password:</strong>

            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}

        </div>

    </div> --}}

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Role:</strong>

            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>
{!! Form::close() !!}
