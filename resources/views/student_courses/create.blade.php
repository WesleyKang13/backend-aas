@extends('layouts.app')
@section('title', 'Add Course')

@section('content')
<div class="container shadow">
    {!! FB::open('/studentcourse/create/'.$student->id, 'POST') !!}
    {!! FB::setErrors($errors) !!}
    @csrf
    <div class="row mt-4 pb-2">
        <div class="col-6">
            <h1>Add Course - {{$student->lastname.' ' .$student->firstname}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/student/{{$student->id}}" class="btn btn-secondary">Back</a>
            {!!FB::submit('Add', [], true)!!}
        </div>

        <div class="col-12">
            @for($i = 1 ; $i < 4 ; $i++)
                {!!FB::select('course_'.$i, 'Course '.$i, $course)!!}</br>
                {!!FB::select('class_'.$i, 'Class '.$i, $class)!!}</br>
            @endfor
        </div>

    </div>

    {!!FB::close() !!}
</div>
@endsection
