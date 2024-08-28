@extends('layouts.app')
@section('title', 'User '.$user->id)

@section('content')
<div class="container shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>{{$user->firstname. ' ' .$user->lastname}}</h1>

        </div>

        <div class="col-6 text-end">
            <a href="/users/{{$user->id}}/timetable" class="btn btn-primary">Timetable</a>
            @if($user->enabled == 1)
                <a href="/users/{{$user->id}}/s?status=disabled" class="btn btn-danger">Disable</a>
            @else
                <a href="/users/{{$user->id}}/s?status=enabled" class="btn btn-success">Enabled</a>
            @endif

            <a href="/users" class="btn btn-secondary">Back</a>
            <a href="/users/edit/{{$user->id}}" class="btn btn-primary">Edit</a>
        </div>

        <table class="table table-hover table-striped">
            <tr>
                <th>Student Id</th>
                <td>{{$user->id}}</td>
            </tr>

            <tr>
                <th>Firstname</th>
                <td>{{$user->firstname}}</td>
            </tr>

            <tr>
                <th>Lastname</th>
                <td>{{$user->lastname}}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{$user->email}}</td>
            </tr>

            <tr>
                <th>Role</th>
                <td>{{ucfirst($user->role)}}</td>
            </tr>

            <tr>
                <th>Enabled</th>
                <td class="badge bg-{{$user->color($user->enabled)}} mt-1">{{($user->enabled == 1) ? 'Yes' : 'No'}}</td>
            </tr>

            <tr>
                <th>Created At</th>
                <td>{{date('Y-M-d H:i', strtotime($user->created_at))}}</td>
            </tr>

            <tr>
                <th>Updated At</th>
                <td>{{date('Y-M-d H:i', strtotime($user->updated_at))}}</td>
            </tr>

            <tr>
                <th>Last Login At</th>
                <td>{{date('Y-M-d H:i', strtotime($user->lastlogin_at))}}</td>
            </tr>

            <tr>
                <th>IP Address</th>
                <td>{{$user->lastlogin_ip}}</td>
            </tr>
        </table>

        <div class="col-6">
            <h1>Courses</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/users/{{$user->id}}/course/create" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="usercourseDT">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Year</th>
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
        $('#usercourseDT').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                //order: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'course_name',},
                    {data: 'course_code',},
                    {data: 'course_year',},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
