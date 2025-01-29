@extends('layouts.app')
@section('title', 'Compose Notification')

@section('content')
<div class="container shadow">
    @if($status == null)
        {!!FB::open('/notifications/compose/'.$user->id, 'POST')!!}
    @elseif($status == 'draft')
        {!!FB::open('/notifications/compose/'.$user->id.'?status=draft', 'POST')!!}
    @endif
        {!!FB::setErrors($errors)!!}

    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Compose New Notification</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/notifications " class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::select('receiver', 'Email', $email)!!}</br>
            {!!FB::input('subject', 'Subject')!!}</br>
            {!!FB::textarea('details', 'Details')!!}</br>
            {!!FB::file('file', 'Attach File *(If Needed)*')!!}</br>
        </div>
    </div>
    {!!FB::close()!!}
</div>
@endsection
