@extends('layouts.app')
@section('title', 'User '.$user->id)

@section('content')
<div class="container shadow">
    <div class="row mt-4">
        <div class="col-6">
            <h1>{{$user->firstname. ' ' .$user->lastname}}</h1>

        </div>

        <div class="col-6 text-end">
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
    </div>
</div>

@endsection
