@extends('layouts.app')
@section('title', 'Timetable '.$timetable->id)

@section('content')
<div class="container shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Timetable - {{$timetable->id}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/timetable/{{$timetable->id}}/addschedule" class="btn btn-primary">Create Schedule</a>
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
                <th>Details / Remarks</th>
                <td>{{$timetable->name}}</td>
            </tr>

            <tr>
                <th>From</th>
                <td>{{date('Y-M-d', strtotime($timetable->from))}}</td>
            </tr>

            <tr>
                <th>To</th>
                <td>{{date('Y-M-d', strtotime($timetable->to))}}</td>
            </tr>

            <tr>
                <th>Created At</th>
                <td>{{date('Y-M-d', strtotime($timetable->created_at))}}</td>
            </tr>

            <tr>
                <th>Updated At</th>
                <td>{{date('Y-M-d', strtotime($timetable->updated_at))}}</td>
            </tr>
        </table>

        <div class="row">
            <div class="col-6">
                <h1>Entries</h1>
            </div>

            <div class="col-12">
                <table class="table table-hover table-striped" id="timetableDT">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#timetableDT').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                order: [[3, 'desc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'day',},
                    {data: 'starttime',},
                    {data: 'endtime',},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
