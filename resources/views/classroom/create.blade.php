@extends('layouts.app')
@section('title', 'Create Classroom')

@section('content')
<div class="container shadow">
    {!! FB::open('/classroom/create', 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Create Classroom</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/classroom" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true)!!}
        </div>

        {!!FB::input('code', 'Code')!!}

    </div>

    {!!FB::close() !!}
</div>
@endsection
