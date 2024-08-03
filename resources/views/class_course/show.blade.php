@extends('layouts.app')
@section('title', 'Edit Class Course '.$class_course->course_id)

@section('content')
<div class="container shadow">
    {!! FB::open('/classcourse/'.$class_course->id.'/edit/'.$class->id, 'POST')!!}
    {!! FB::setInput($class_course)!!}
    {!! FB::setErrors($errors) !!}

    @csrf
    <div class="row mt-4">
        <div class="col-8">
            <h1>Edit Course {{$class_course->course->name}} - {{$class->code}}</h1>
        </div>

        <div class="col-4 text-end mt-2">
            <a href="/classroom/{{$class_course->class_id}}" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12 pb-2">
            {!! FB::select('course_id', 'Course', $course)!!}

        </div>

    </div>

    {!!FB::close()!!}
</div>

@endsection
