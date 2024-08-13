@extends('layouts.app')
@section('title', 'Lecturer '.$lecturer->id)

@section('content')
<div class="container shadow">

    {!!FB::open('/lecturer/'.$lecturer->id.'/edit', 'Post')!!}
    {!!FB::setErrors($errors)!!}
    {!!FB::setInput($lecturer)!!}
    @csrf
    <div class="row mt-4">

        <div class="col-6">
            <h1>{{$lecturer->lastname.' '.$lecturer->firstname}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturer/{{$lecturer->id}}/timetable" class="btn btn-warning">Timetables</a>
            @if($lecturer->enabled == 1)
                <a href="/lecturer/{{$lecturer->id}}/status?status=disabled" class="btn btn-danger">Disable</a>
            @else
                <a href="/lecturer/{{$lecturer->id}}/status?status=enabled" class="btn btn-success">Enable</a>
            @endif

            <a href="/lecturer" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <table class="table table-hover table-striped">
            <tr>
                <th>lecturer Id:</th>
                <td>{{$lecturer->id}}</td>
            </tr>

            <tr>
                <th>lecturer Firstname:</th>
                <td style="width:50%;">{!!FB::input('firstname', '')!!}</td>
            </tr>

            <tr>
                <th>lecturer Lastname:</th>
                <td style="width:50%;">{!!FB::input('lastname', '')!!}</td>
            </tr>

            <tr>
                <th>Enabled:</th>
                <td><span class="badge bg-{{$lecturer->color($lecturer->enabled)}}">{{($lecturer->enabled == 1) ? 'Yes' : 'No'}}</span></td>
            </tr>

            <tr>
                <th>Class Created At:</th>
                <td>{{date('Y-M-d H:i', strtotime($lecturer->created_at))}}</td>
            </tr>

            <tr>
                <th>Class Updated At:</th>
                <td>{{date('Y-M-d H:i', strtotime($lecturer->updated_at))}}</td>
            </tr>
        </table>

    </div>

    {!!FB::close()!!}
    <div class="row">
        <div class="col-6">
            <h1>Courses</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturercourse/create/{{$lecturer->id}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="classCourse">
                <thead>
                    <tr>
                        <th>Class Code</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Created At</th>
                        <th>Updated At</th>
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
            $('#classCourse').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                order: [[3, 'asc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'code',},
                    {data: 'name'},
                    {data: 'year',},
                    {data: 'created_at'},
                    {data: 'updated_at'},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
        });
</script>
@endpush

