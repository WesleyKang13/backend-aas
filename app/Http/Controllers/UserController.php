<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller{
    public function index(){
        $role = request()->get('r');
        if(request()->ajax()){
            $rows = User::query();

            if($role !== null){
                $rows->where('role', $role);
            }

            return DataTables::of($rows)
                    ->editColumn('created_at', function ($r){
                        return date('Y-M-d H:i', strtotime($r->created_at));
                    })
                    ->editColumn('role', function($r){
                        return ucfirst($r->role);
                    })
                    ->addColumn('action', function ($r){
                        return '<a href="/users/'.$r->id.'" class="btn btn-primary btn-small">Manage</a>';
                    })
                    ->rawColumns(['action'])
                    ->make('true');
        }

        return view('user.index');
    }

    public function show($id){
        $user = User::findOrFail($id);

        return view('user.show')->with('user', $user);
    }

    public function status($id){
        $status = request()->get('status');

        $valid_status = ['enabled', 'disabled'];

        if(!in_array($status, $valid_status)){
            return back()->withError('Invalid status');
        }

        $user = User::FindOrFail($id);
        if($status == 'enabled'){
            $user->enabled = 1;
        }else{
            $user->enabled = 0;
        }

        $user->save();

        return redirect('/users/'.$user->id)->withSuccess('User has been successfully '.$status);
    }

    public function create(){
        $roles = [
            null => 'Please select a role',
            'student' => 'Student',
            'lecturer' => 'Lecturer',
            'admin' => 'Admin'

        ];

        return view('user.create')->with('roles', $roles);
    }

    public function store(){
        $valid = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:student,lecturer,admin'
        ]);

        // hash password
        $password = Hash::make($valid['password']);

        $user = new User();
        $user->firstname = $valid['firstname'];
        $user->lastname = $valid['lastname'];
        $user->email = $valid['email'];
        $user->password = $password;
        $user->role = $valid['role'];
        $user->save();

        return redirect('/users/'.$user->id)->withSuccess('New '.$user->role.' created successfully');
    }

    public function edit($id){
        $user = User::findOrFail($id);

        $roles = [
            null => 'Please select a role',
            'student' => 'Student',
            'lecturer' => 'Lecturer',
            'admin' => 'Admin'
        ];

        return view('user.edit')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update($id){
        $valid = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:student,lecturer,admin'
        ]);

        $user = User::findOrFail($id);
        $user->firstname = $valid['firstname'];
        $user->lastname = $valid['lastname'];
        $user->email = $valid['email'];

        if($valid['password'] != null){
            $password = Hash::make($valid['password']);

            $user->password = $password;
        }

        $user->role = $valid['role'];
        $user->save();

        return redirect('/users/'.$user->id)->withSuccess('User updated successfully');
    }

}
