@extends('layouts.app')
@section('title', 'Create Timetable')

@section('content')
<div class="container shadow">
    {!!FB::open('/timetable/create', 'post')!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Create Timetable</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/timetable" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true);!!}
        </div>

        @if(isset($student))
            {!!FB::hidden('student_id', 'Class', $student->id)!!}</br>
        @endif

        <div class="col-12">
            {!!FB::select('class_id', 'Class', $classes)!!}</br>
            {!!FB::select('course_id', 'Course', $courses)!!}</br>
            {!!FB::input('name', 'Name')!!}</br>
            {!!FB::date('from', 'From')!!}</br>
            {!!FB::date('to', 'To')!!}</br>
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
