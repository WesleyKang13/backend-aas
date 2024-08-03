@extends('layouts.app')
@section('title', 'Timetable '.$timetable->id)

@section('content')
<div class="container shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Timetable - {{$timetable->id}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/timetable" class="btn btn-secondary">Back</a>
        </div>

        <table class="table table-hover table-striped">
            <tr>
                <th>Classroom Code</th>
                <td>{{$timetable->classroom->code}}</td>
            </tr>

            <tr>
                <th>Course Name</th>
                <td>{{$timetable->course->name}}</td>
            </tr>

            <tr>
                <th>Week Number</th>
                <td>{{$timetable->week_number}}</td>
            </tr>

            <tr>
                <th>Day</th>
                <td>{{$timetable->day}}</td>
            </tr>

            <tr>
                <th>Year</th>
                <td>{{$timetable->year}}</td>
            </tr>
            
            <tr>
                <th>Start Time</th>
                <td>{{$timetable->start_time}}</td>
            </tr>

            <tr>
                <th>End Time</th>
                <td>{{$timetable->end_time}}</td>
            </tr>

            <tr>
                <th>Enabled</th>
                <td><span class="badge {{($timetable->enabled == 1) ? 'bg-success' : 'bg-danger'}}">{{($timetable->enabled == 1)? 'Yes' : 'No'}}</span></td>
            </tr>

            <tr>
                <th>Created At</th>
                <td>{{date('Y-M-d H:i:s', strtotime($timetable->created_at))}}</td>
            </tr>

            <tr>
                <th>Updated At</th>
                <td>{{date('Y-M-d H:i:s', strtotime($timetable->updated_at))}}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
