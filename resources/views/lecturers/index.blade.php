@extends('layouts.app')
@section('title', 'Lecturer')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-6">
            <h1>Manage Lecturers</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/lecturer/create" class="btn btn-primary"><i class="fa fa-plus"></i> Create Lecturer</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="lecturerDT">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>firstname</th>
                        <th>Lastname</th>
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
        $('#lecturerDT').DataTable({
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
                    {data: 'firstname',},
                    {data: 'lastname',},
                    {data: 'enabled'},
                    {data: 'created_at',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush


