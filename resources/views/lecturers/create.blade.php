@extends('layouts.app')
@section('title', 'Create Lecturer')

@section('content')
<div class="container shadow">
    {!! FB::open('/lecturer/create', 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Create Lecturer</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturer" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::input('firstname', 'Firstname')!!}</br>
            {!!FB::input('lastname', 'Lastname')!!}</br>
        </div>


    </div>

    {!!FB::close() !!}
</div>
@endsection
