@extends('layouts.app')
@section('title', $student->lastname. ' '.$student->firstname.' timetable')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Manage {{$student->lastname. ' ' .$student->firstname}}'s Timetable</h1>
        </div>

        <div class="col-6 text-end">
            <a href="/student/{{$student->id}}" class="btn btn-secondary">Back</a>
            <a href="/studenttimetable/create/{{$student->id}}" class="btn btn-primary">Add Timetable</a>
        </div>

        <table class="table table-hover table-striped" id="studentimetableDT">
            <thead>
                <tr>
                    <th>Timetable Id</th>
                    <th>Class Code</th>
                    <th>Course Name</th>
                    <th>Course Year</th>
                    <th>Week Number</th>
                    <th>Day</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#studentimetableDT').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                order: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'timetable_id',},
                    {data: 'class',},
                    {data: 'course',},
                    {data: 'year'},
                    {data: 'week_number',},
                    {data: 'day',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
