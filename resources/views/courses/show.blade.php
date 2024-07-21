@extends('layouts.app')
@section('title', 'Course '.$course->id)

@section('content')
<div class="container shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>{{$course->name}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/course" class="btn btn-secondary">Back</a>
            <a href="/course/{{$course->id}}/edit" class="btn btn-primary">Edit</a>
        </div>

        <div class="col-12">
            <table class="table-hover table-striped table">
                <tr>
                    <th>Name</th>
                    <td>{{$course->name}}</td>
                </tr>
                
                <tr>
                    <th>Total Student</th>
                    <td>{{$course->total_student}}</td>
                </tr>

                <tr>
                    <th>Year</th>
                    <td>{{$course->year}}</td>
                </tr>

                <tr>
                    <th>Enabled</th>
                    <td><span class="badge bg-{{$course->color($course->enabled)}}">{{($course->enabled == 1) ? 'Yes' : 'No'}}</span></td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{date('Y-M-d', strtotime($course->created_at))}}</td>
                </tr>

                <tr>
                    <th>Updated At</th>
                    <td>{{date('Y-M-d', strtotime($course->updated_at))}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection