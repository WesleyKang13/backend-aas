@extends('layouts.app')
@section('title', 'Login')

@section('content')

<div class="container shadow">
    {!!FB::open('/login', 'post')!!}
    @csrf
    <div class="row mt-5">
        <div class="col-6">
            <h1>Automated Attendance System</h1>
        </div>

        <div class="col-6 text-end mt-2">
            {!!FB::submit('Login', [], true)!!}
        </div>


        <div class="col-12 pb-2">
            {!!FB::input('email', 'Email')!!}</br>
            {!!FB::password('password', 'Password')!!}
        </div>


    </div>
    {!!FB::close()!!}
</div>

@endsection
