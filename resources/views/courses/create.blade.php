@extends('layouts.app')
@section('title', 'Create Course')

@section('content')
<div class="container shadow">
    {!! FB::open('/course/create', 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Create Course</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/course" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true)!!}
        </div>

        <div class="col-12">
            {!!FB::input('name', 'Name')!!}</br>
            {!!FB::input('total_student', 'Total Student')!!}</br>
            {!!FB::input('year', 'Year')!!}
        </div>
        

    </div>

    {!!FB::close() !!}
</div>
@endsection
