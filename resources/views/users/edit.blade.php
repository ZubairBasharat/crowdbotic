@extends('layouts.app')

@section('content')

<div class="main-padding">
    <div class="table-card">
        <h1 class="heading">Create User</h1>
        @include('flash::message')
        @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mt-4">{{$error}}</div>
            @endforeach
        @endif
        {!! Form::open(['route' => ['users.update', $user], 'method'=>'put', 'files' => true]) !!}

            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-6">
                    {{ Form::label('name', 'Full Name', ['class' => 'theme-label required']) }}
                    {{ Form::text('name',  $user->name, ['class' => 'theme-input']) }}
                    {{-- <label for="" class="theme-label">Full Name</label>
                    <input type="text" placeholder="Full name" name="name" class="theme-input"> --}}
                </div>
                <div class="col-md-6">
                    {{ Form::label('email', 'Email', ['class' => 'theme-label required']) }}
                    {{ Form::text('email',  $user->email, ['class' => 'theme-input']) }}
                </div>
                <div class="col-md-6">
                    {{ Form::label('password', 'Password', ['class' => 'theme-label required']) }}
                    {{ Form::Password('password',  ['class'=>'theme-input' ]) }}
                </div>

                <div class="col-md-6">
                    {{ Form::label('phone_number', 'Contact Number', ['class' => 'theme-label required']) }}
                    {{ Form::text('phone_number',  $user->phone_number, ['class' => 'theme-input']) }}

                </div>
                <div class="col-md-6">
                    <label for="" class="theme-label">Role</label>
                    <select class="theme-input" name="roles">
                        <option value="">Select Role</option>
                        @foreach($roles as  $id => $name)
                            <option  <?php echo ($id == $userRole)?'selected':'';?> value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="theme-btn ms-2 cancel" style="width: 100px;">Cancel</button>
                <button class="theme-btn ms-2" type="submit" style="width: 100px;">Save</button>

            </div>
            {!! Form::close() !!}
        </div>
</div>
@endsection
{{-- @extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
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


{!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permission:</strong>
            <br/>
            @foreach($permission as $value)
                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                {{ $value->name }}</label>
            <br/>
            @endforeach
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}


@endsection --}}