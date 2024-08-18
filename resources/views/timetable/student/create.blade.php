@extends('layouts.app')
@section('title', 'Create Timetable')

@section('content')
<div class="container shadow">
    {!!FB::open('/studenttimetable/create/'.$student->id, 'post')!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row mt-4">
        <div class="col-6">
            <h1>Add Timetable To {{$student->lastname .' '.$student->firstname}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/studenttimetable/{{$student->id}}/timetable" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true);!!}
        </div>

        <div class="col-12">
            {!!FB::select('timetable_id', 'Timetable', $timetable)!!}</br>
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
