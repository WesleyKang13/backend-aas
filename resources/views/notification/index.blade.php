@extends('layouts.app')
@section('title', 'Notification')

@section('content')
<div class="container-fluid">
    <h1>TO BE DONE (
        Inbox, Sent, Unread Messages, Read Messages, Draft)</h1>
    <div class="row mt-4 m-auto">
        <div class="col-6">
            <h1>Inbox</h1>
        </div>

        <div class="col-6 text-end mt-2">
            <a href="/notifications/compose/{{$user->id}}" class="btn btn-primary">Compose</a>
            <a href="/notifications/readall" class="btn btn-danger">Mark All As Read</a>
        </div>

        <div class="col-12">
            <table class="table table-hover table-striped" id="notificationDT">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Subject</th>
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
        $('#notificationDT').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",
                order: [[1, 'desc']],
                processing: true,
                serverSide: true,
                ajax: '{{Request::fullUrl()}}',
                pageLength: 50,
                columnDefs: [
                    // {className: 'dt-center', targets: [1, 3]},
                ],
                columns: [
                    {data: 'user_id',},
                    {data: 'datetime',},
                    {data: 'status',},
                    {data: 'subject',},
                    {data: 'action', orderable: false, searchable: false,},
                ],
            });
    });
</script>
@endpush
