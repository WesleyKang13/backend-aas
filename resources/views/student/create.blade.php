@extends('layouts.app')
@section('title', 'Create Student')

@section('content')
<div class="container shadow">
    {!! FB::open('/student/create', 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Create Student</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/student" class="btn btn-secondary">Back   </a>
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
