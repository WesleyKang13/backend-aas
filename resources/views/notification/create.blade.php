@extends('layouts.app')
@section('title', 'Compose Notification')

@section('content')
<div class="container shadow">
    {!!FB::open('/notifications/compose/'.$user->id, 'POST')!!}
    {!!FB::setErrors($errors)!!}

    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Compose New Notification</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/notication" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::select('email', 'Email', $email)!!}</br>
            {!!FB::input('subject', 'Subject')!!}</br>
            {!!FB::input('details', 'Details')!!}</br>
            {!!FB::file('file', 'Attach File *(If Needed)*')!!}</br>
        </div>
    </div>
    {!!FB::close()!!}
</div>
@endsection
