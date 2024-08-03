@extends('layouts.app')
@section('title', 'Edit '.$lecturer->lastname.' Course')

@section('content')
<div class="container shadow">
    {!!FB::open('/lecturercourse/'.$lecturer_course->id.'/edit', 'post')!!}
    {!!FB::setInput($lecturer_course)!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Edit {{$lecturer->lastname. ' '.$lecturer->firstname}}'s Course</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturer/{{$lecturer->id}}" class="btn btn-secondary">Back</a>
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
