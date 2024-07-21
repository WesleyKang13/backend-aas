@extends('layouts.app')
@section('title', 'Edit '.$student->lastname.' Course')

@section('content')
<div class="container shadow">
    {!!FB::open('/studentcourse/'.$student_course->id.'/edit', 'post')!!}
    {!!FB::setInput($student_course)!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Edit {{$student->lastname. ' '.$student->firstname}}'s Course</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/student/{{$student->id}}" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <div class="col-12 pb-2">
            {!!FB::select('class_id', 'Class Code', $classes)!!}</br>
            {!!FB::select('course_id', 'Course Name', $courses)!!}
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
