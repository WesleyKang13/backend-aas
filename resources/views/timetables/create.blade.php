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

        <div class="col-12">
            {!!FB::select('class_id', 'Class', $classes)!!}</br>
            {!!FB::select('course_id', 'Course', $courses)!!}</br>
            {!!FB::input('week_number', 'Week Number')!!}</br>
            {!!FB::select('day', 'Day', $days)!!}</br>
            {!!FB::input('year', 'Year')!!}</br>
            {!!FB::date('date', 'Date')!!}</br>
        </div>

        <div class="col-3">
            <label for="start_time">Start Time</label>
        </div>

        <div class="col-3 text-end">
            <input type="time" name="start_time" style="width:100%; border-radius:5px; padding:5px;"></br>

            @if ($errors->has('start_time'))
                <span class="text-danger">{{ $errors->first('start_time') }}</span>
            @endif
        </div>

        <div class="col-3">
            <label for="end_time">End Time</label>
        </div>

        <div class="col-3 text-end pb-3">
            <input type="time" name="end_time" style="width:100%; border-radius:5px; padding:5px;"></br>

            @if ($errors->has('end_time'))
                <span class="text-danger text-start">{{ $errors->first('end_time') }}</span>
            @endif
        </div>

    </div>
    {!!FB::close()!!}
</div>
@endsection
