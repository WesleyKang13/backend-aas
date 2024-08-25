@extends('layouts.app')
@section('title', 'Edit User '.$user->id)

@section('content')
<div class="container shadow">
    {!! FB::open('/users/edit/'.$user->id, 'Post')!!}
    {!! FB::setErrors($errors)!!}
    {!! FB::setInput($user)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Edit User {{$user->firstname. ' '.$user->lastname}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/users"  class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::input('firstname', 'Firstname')!!}<br>
            {!!FB::input('lastname', 'Lastname')!!}<br>
            {!!FB::input('email', 'Email')!!}<br>
            {!!FB::password('password', 'Password', ['placeholder' => 'Enter new password if need change'])!!}<br>
            {!!FB::select('role', 'Role', $roles)!!}<br>
        </div>

    </div>

    {!!FB::close()!!}
</div>
@endsection
