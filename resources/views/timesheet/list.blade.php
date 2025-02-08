@extends('layouts.app')
@section('title', 'Attendance list on '.$date)

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Attendance List On {{date('Y-m-d', strtotime($date))}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/timesheet?month={{date('m', strtotime($date))}}" class="btn btn-secondary">Back</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="timesheetDT">
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Course</th>
                        <th>Time Submitted</th>
                        <th>IP Address</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#timesheetDT').DataTable({
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
                    {data: 'users.firstname',},
                    {data: 'users.lastname',},
                    {data: 'courses.name',},
                    {data: 'timestamp',},
                    {data: 'ip_address',},
                    {data: 'users.role'},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
