@extends('layouts.app')
@section('title', 'Timetables')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Manage Timetables</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="" class="btn btn-success">Import</a>
            <a href="" class="btn btn-danger">Export</a>
            <a href="/timetable/create" class="btn btn-primary"><i class="fa fa-plus"></i> Create Timetable</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="timetableDT">
                <thead>
                    <tr>
                        <th>Class Code</th>
                        <th>Course Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
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
                order: [[4, 'desc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'class_id',},
                    {data: 'course_id',},
                    {data: 'from',},
                    {data: 'to',},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
