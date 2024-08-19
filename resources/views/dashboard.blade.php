@extends('layouts.app')
@section('Title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-6">
                <h1>Total Students Clock In - {{date('Y-M-d')}}</h1>
                <h1>Total Lecturers Clock In - {{date('Y-M-d')}}</h1>
            </div>
        </div>
    </div>
@endsection
