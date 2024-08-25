@extends('layouts.app')
@section('title', 'User Manager')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-3">
            <h1>Manage Users</h1>
        </div>

        <div class="col-5 mt-3 d-flex">
            <div class="col-3">
                <h3>Filter role :</h3>
            </div>

            <div class="col-9">
                <a href="/users" class="btn btn-secondary">All</a>
                <a href="/users?r=student" class="btn btn-primary">Student</a>
                <a href="/users?r=lecturer" class="btn btn-warning">Lecturer</a>
            </div>

        </div>

        <div class="col-4 text-end mt-2">
            <a href="/users/create" class="btn btn-primary"><i class="fa fa-plus"></i> Create User</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="userDT">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
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
        $('#userDT').DataTable({
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
                    {data: 'email',},
                    {data: 'firstname',},
                    {data: 'lastname',},
                    {data: 'role'},
                    {data: 'enabled',},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
