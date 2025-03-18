@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<div class="container shadow">
    {!! FB::open('/users/create', 'Post')!!}
    {!! FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Create User</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/users"  class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::input('firstname', 'Firstname')!!}<br>
            {!!FB::input('lastname', 'Lastname')!!}<br>
            {!!FB::input('email', 'Email')!!}<br>
            {!!FB::password('password', 'Password', ['Placeholder' => 'Auto Generate Password if left empty'])!!}<br>
            {!!FB::select('role', 'Role', $roles)!!}<br>
        </div>

    </div>

    {!!FB::close()!!}
</div>
@endsection
