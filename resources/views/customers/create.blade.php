@extends('layouts.app')

@section('content')

<div class="main-padding">
    <div class="table-card">
        <h1 class="heading">Create Customers</h1>
        @include('flash::message')
        @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mt-4">{{$error}}</div>
            @endforeach
        @endif
        {!! Form::open(['route' => 'customers.store']) !!}

            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-6">
                    {{ Form::label('name', 'Full Name', ['class' => 'theme-label required']) }}
                    {{ Form::text('name', null, ['class' => 'theme-input']) }}

                </div>
                <div class="col-md-6">
                    {{ Form::label('email', 'Email', ['class' => 'theme-label required']) }}
                    {{ Form::text('email', null, ['class' => 'theme-input']) }}
                </div>
                <div class="col-md-6">
                    {{ Form::label('phone_number', 'Contact Number', ['class' => 'theme-label required']) }}
                    {{ Form::text('phone_number', null, ['class' => 'theme-input']) }}

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