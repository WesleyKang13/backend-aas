@extends('layouts.app')
@section('title', 'Edit Course '.$course->id)

@section('content')
<div class="container shadow">
    {!!FB::open('/course/'.$course->id.'/edit', 'post')!!}
    {!!FB::setInput($course) !!}
    {!!FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4">
        <div class="col-8">
            <h1>Edit {{$course->name}}</h1>
        </div>

        <div class="col-4 text-end mt-2">
            <a href="/course/{{$course->id}}" class="btn btn-secondary">Back</a>
            {!!FB::submit('Update', [], true)!!}
        </div>

        <div class="col-12 mt-3">
            {!!FB::input('name', 'Name')!!}</br>
            {!!FB::input('code', 'Code')!!}</br>
            {!!FB::input('total_student', 'Total Student')!!}</br>
            {!!FB::input('year', 'Year')!!}</br>
            {!!FB::select('enabled', 'Enabled', $enabled)!!}</br>
        </div>
    </div>
    {!!FB::close()!!}
</div>
@endsection
