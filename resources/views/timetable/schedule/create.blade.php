@extends('layouts.app')
@section('title', 'Create Schedule - Timetable '.$timetable->id)

@section('content')
<div class="container shadow">
    {!!FB::open('/timetable/'.$timetable->id.'/addschedule', 'Post')!!}
    {!!FB::setErrors($errors)!!}
    @csrf
    <div class="row">
        <div class="col-6">
            <h1>Create Schedule Timetable Id - {{$timetable->id}}</h1>
        </div>

        <div class="col-6 text-end">
            <a href="" class="btn btn-secondary">Back</a>
            {!!FB::submit('Create', [], true)!!}
        </div>

        <div class="col-12 ">
            {!!FB::select('day', 'Day', $days)!!}</br>
        </div>

        <div class="col-12 d-flex">
            <div class="col-3">
                <label for="starttime">Start Time</label>
            </div>

            <div class="col-3 text-end">
                <input type="time" name="starttime" style="width:100%; border-radius:5px; padding:5px;"></br>

                @if ($errors->has('start_time'))
                    <span class="text-danger">{{ $errors->first('start_time') }}</span>
                @endif
            </div>

            <div class="col-3">
                <label for="endtime">End Time</label>
            </div>

            <div class="col-3 text-end pb-3">
                <input type="time" name="endtime" style="width:100%; border-radius:5px; padding:5px;"></br>

                @if ($errors->has('end_time'))
                    <span class="text-danger text-start">{{ $errors->first('end_time') }}</span>
                @endif
            </div>
        </div>
    </div>
    {!!FB::close()!!}
</div>
@endsection
