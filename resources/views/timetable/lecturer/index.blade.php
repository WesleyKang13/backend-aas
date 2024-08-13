@extends('layouts.app')
@section('title', $lecturer->lastname.$lecturer->firstname. ' timetable');

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-6">
            <h1>{{$lecturer->lastname . ' ' .$lecturer->firstname}}'s Timetable</h1>
        </div>

        <div class="col-6 text-end">
            <a href="/lecturer/{{$lecturer->id}}" class="btn btn-secondary">Back</a>
            <a href="/lecturertimetable/create/{{$lecturer->id}}" class="btn btn-primary">Add Timetable</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="lecturertimetableDT">
                <thead>
                    <tr>
                        <tr>
                            <th>Timetable Id</th>
                            <th>Class Code</th>
                            <th>Course Name</th>
                            <th>Course Year</th>
                            <th>Week Number</th>
                            <th>Day</th>
                            <th>Action</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#lecturertimetableDT').DataTable({
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
