@extends('layouts.app')
@section('title', 'Class '.$class->id)

@section('content')
<div class="container shadow">

    {!!FB::open('/classroom/edit/'.$class->id, 'Post')!!}
    {!!FB::setErrors($errors)!!}
    {!!FB::setInput($class)!!}
    @csrf
    <div class="row mt-4">

        <div class="col-6">
            <h1>{{$class->code}}</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/classroom" class="btn btn-secondary">Back</a>
            {!!FB::submit('Save', [], true)!!}
        </div>

        <table class="table table-hover table-striped">
            <tr>
                <th>Class Id:</th>
                <td>{{$class->id}}</td>
            </tr>

            <tr>
                <th>Class Code:</th>
                <td style="width:50%;">{!!FB::input('code', '')!!}</td>
            </tr>

            <tr>
                <th>Class Created At:</th>
                <td>{{date('Y-M-d H:i', strtotime($class->created_at))}}</td>
            </tr>

            <tr>
                <th>Class Updated At:</th>
                <td>{{date('Y-M-d H:i', strtotime($class->updated_at))}}</td>
            </tr>
        </table>

    </div>

    {!!FB::close()!!}
    {{-- <div class="row">
        <div class="col-6">
            <h1>Courses</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/classcourse/create/{{$class->id}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="classCourse">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Total Student</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div> --}}
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
                    {data: 'name',},
                    {data: 'year'},
                    {data: 'total_student',},
                    {data: 'created_at'},
                    {data: 'updated_at'},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
        });
</script>
@endpush

