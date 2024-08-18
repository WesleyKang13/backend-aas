@extends('layouts.app')
@section('title', 'Edit Timetable '.$student_timetable->id)

@section('content')
<div class="container shadow">
    {!!FB::open('/studenttimetable/'.$student_timetable->id.'/edit', 'post')!!}
    {!!FB::setErrors($errors)!!}
    {!!FB::setInput($student_timetable)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Edit {{$student_timetable->student->lastname . ' ' .$student_timetable->student->firstname}}'s Timetable {{$student_timetable->id}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/student/{{$student_timetable->student_id}}/timetable" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true);!!}
        </div>

        <div class="col-12">
            {!!FB::select('timetable_id', 'Timetable', $timetable)!!}</br>
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
