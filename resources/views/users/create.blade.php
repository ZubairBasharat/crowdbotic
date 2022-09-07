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
        {!! Form::open(['route' => 'users.store']) !!}

            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-6">
                    {{ Form::label('name', 'Full Name', ['class' => 'theme-label required']) }}
                    {{ Form::text('name', null, ['class' => 'theme-input']) }}
                    {{-- <label for="" class="theme-label">Full Name</label>
                    <input type="text" placeholder="Full name" name="name" class="theme-input"> --}}
                </div>
                <div class="col-md-6">
                    {{ Form::label('email', 'Email', ['class' => 'theme-label required']) }}
                    {{ Form::text('email', null, ['class' => 'theme-input']) }}
                </div>
                <div class="col-md-6">
                    {{ Form::label('password', 'Password', ['class' => 'theme-label required']) }}
                    {{ Form::Password('password',  ['class'=>'theme-input' ]) }}
                </div>

                <div class="col-md-6">
                    {{ Form::label('phone_number', 'Contact Number', ['class' => 'theme-label required']) }}
                    {{ Form::text('phone_number', null, ['class' => 'theme-input']) }}

                </div>
                <div class="col-md-6">
                    <label for="" class="theme-label">Role</label>
                    <select class="theme-input" name="roles">
                        <option value="">Select Role</option>
                        @foreach($roles as  $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
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