@extends('layouts.app')
@section('title', 'Edit Timetable '.$lecturer_timetable->id)

@section('content')
<div class="container shadow">
    {!!FB::open('/lecturertimetable/'.$lecturer_timetable->id.'/edit', 'post')!!}
    {!!FB::setErrors($errors)!!}
    {!!FB::setInput($lecturer_timetable)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Edit {{$lecturer_timetable->lecturer->lastname . ' ' .$lecturer_timetable->lecturer->firstname}}'s Timetable {{$lecturer_timetable->id}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturer/{{$lecturer_timetable->lecturer_id}}/timetable" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true);!!}
        </div>

        <div class="col-12">
            {!!FB::select('timetable_id', 'Timetable', $timetable)!!}</br>
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
