<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\UserCourse;
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

        if(request()->ajax()){
            $rows = UserCourse::query()->where('user_id', $user->id);

            return DataTables::of($rows)
                ->addColumn('course_name', function($r){
                    return $r->course->name;
                })
                ->addColumn('course_code', function($r){
                    return $r->course->code;
                })
                ->addColumn('course_year', function ($r){
                    return $r->course->year;
                })
                ->editColumn('created_at', function ($r){
                    return date('Y-M-d H:i:s', strtotime($r->created_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/usercourse/'.$r->id.'/delete" class="btn btn-danger">Delete</a>';
                })
                ->rawColumns(['action', 'enabled'])
                ->make('true');

        }

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

    //assign course to user
    public function add($id){
        $user = User::findOrFail($id);

        $courses = Course::query()->where('enabled', 1)->get();

        $course = [null => 'Please choose/select a course'];

        foreach($courses as $c){
            $course[$c->id] = '('.$c->code. ') '.$c->name. ' - Year '.$c->year;
        }

        return view('user.assign')->with([
            'course' => $course,
            'user' => $user
        ]);
    }

    public function assign($id){
        $rules = [];

        for($i = 1 ; $i < 4 ; $i++){
            $rules['course_id_'.$i] = 'nullable|exists:courses,id';
        }

        $valid = request()->validate($rules);

        $user = User::findOrFail($id);

        foreach($valid as $k => $v){
            if($v != null){

                if(count($user->user_course) > 0){
                    foreach($user->user_course as $course){
                        if($course->course_id == $v){
                            return back()->withError('Course is assigned to this user!');
                        }
                    }
                }

                $course = Course::findOrFail($v);
                $new = new UserCourse();
                $new->course_id = $v;
                $new->user_id = $user->id;
                $new->save();
                $course->total_student += 1;
                $course->save();
            }

        }

        return redirect('/users/'.$user->id)->withSuccess('Courses Added Successfully');
    }

    public function delete($user_course_id){
        $user_course = UserCourse::findOrFail($user_course_id);

        $user_course->delete();

        return redirect('/users/'.$user_course->user_id)->withSuccess('Course Deleted Successfully');
    }

}
