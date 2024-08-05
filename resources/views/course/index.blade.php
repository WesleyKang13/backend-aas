@extends('layouts.app')
@section('title', 'Classrooms')

@section('content')
<div class="container-fluid shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Manage Courses</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/course/create" class="btn btn-primary"><i class="fa fa-plus"></i> Create Course</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="coursesDT">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Course Name</th>
                        <th>Course Code</th>
                        <th>Total Student</th>
                        <th>Year</th>
                        <th>Enabled</th>
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
            $('#coursesDT').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'id'},
                    {data: 'name',},
                    {data: 'code',},
                    {data: 'total_student',},
                    {data: 'year'},
                    {data: 'enabled'},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
        });
</script>
@endpush

