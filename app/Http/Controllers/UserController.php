<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTimetable;
use App\Models\Timetable;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                    ->editColumn('enabled', function($r){
                        if($r->enabled == 1){
                            return '<span class="badge bg-success">Yes</span>';
                        }

                        return '<span class="badge bg-danger">No</span>';
                    })
                    ->addColumn('action', function ($r){
                        return '<a href="/users/'.$r->id.'" class="btn btn-primary btn-small">Manage</a>';
                    })
                    ->rawColumns(['action', 'enabled'])
                    ->make('true');
        }

        return view('user.index');
    }

    public function show($id){
        $user = User::findOrFail($id);

        $user_timetables = UserTimetable::query()->where('user_id', $user->id)->get();

        $user_timetable = [];

        foreach($user_timetables as $ut){
            $timetable = Timetable::findOrFail($ut->timetable_id);
            $class = Classroom::findOrFail($timetable->class_id);
            $course = Course::findOrFail($timetable->course_id);

            $user_timetable[] = [
                'timetable_id' => $ut->timetable_id,
                'class_code' => $class->code,
                'course_name' => $course->name,
                'course_year' => $course->year,
                'from' => date('Y-m-d' , strtotime($timetable->from)),
                'to' => date('Y-m-d', strtotime($timetable->to)),
                'action' => '<a href="/users/'.$ut->id.'/timetable/delete" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }

        if(request()->ajax()){
            return DataTables::of($user_timetable)
                ->editColumn('timetable_id', function($r){
                    return '<a href=/timetable/'.$r['timetable_id'].'>'.$r['timetable_id'].'</a>';
                })
                ->editColumn('class_code', function($r){
                    return $r['class_code'];
                })
                ->editColumn('course_name', function($r){
                    return $r['course_name'];
                })
                ->editColumn('course_year', function($r){
                    return $r['course_year'];
                })
                ->editColumn('from', function($r){
                    return $r['from'];
                })
                ->editColumn('to', function($r){
                    return $r['to'];
                })
                ->addColumn('action', function($r){
                    return $r['action'];
                })
                ->rawColumns(['action', 'timetable_id', 'week_number','day'])
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

        // password for email
        $p = $valid['password'];

        // hash password
        $password = Hash::make($valid['password']);

        $user = new User();
        $user->firstname = $valid['firstname'];
        $user->lastname = $valid['lastname'];
        $user->email = $valid['email'];
        $user->password = $password;
        $user->role = $valid['role'];
        $user->save();

        $data = [
            'user' => $user,
            'password' => $p
        ];

        Mail::send('user.mail', $data, function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Account Created');
        });

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

    public function import(){
        return view('user.import');
    }

    public function upload(){
        $valid = request()->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $line = 0;
        $success = [];
        $errors = [];
        $skip = [];

        if(($handle = fopen($valid['file'], "r")) !== FALSE){

            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                    // get headers
                    if($line == 0){
                        $csv = ["Email","Firstname","Lastname","Password","Role"];

                        $data = str_replace(' ', '', $data);

                        if($data !== $csv){
                            @$errors[$line] = 'Header does not match. Invalid';
                            break;
                        }
                        @$success[$line] = 'Header is valid';

                    }else{
                        // initialize data
                        $email = @trim($data[0]);
                        $firstname = @trim($data[1]);
                        $lastname = @trim($data[2]);
                        $password = @trim($data[3]);
                        $role = @trim($data[4]);

                        if($email == null){
                            @$errors[$line] = "Email is empty";
                        }elseif($firstname == null){
                            @$errors[$line] = "Firstname is empty";
                        }elseif($lastname == null){
                            @$errors[$line] = "Lastname is empty";
                        }elseif($password == null){
                            @$errors[$line] = "Password is empty";
                        }elseif($role == null){
                            @$errors[$line] = "Role is empty";
                        }else{
                            // get all existing emails that are enabled
                            $users = User::query()->where('enabled', 1)->get();
                            $existing_users = [];

                            foreach($users as $u){
                                $existing_users[] = $u->email;
                            }

                            if(!in_array($email, $existing_users)){
                                $user = new User();
                                $user->email = $email;
                                $user->firstname = $firstname;
                                $user->lastname = $lastname;
                                $user->password = Hash::make($password);
                                $user->role = $role;
                                $user->save();

                                @$success[$line] = 'User '.$email.' created successfully';
                            }else{
                                @$skip[$line] = 'User '.$email.' already exists';
                            }
                        }

                    }
                $line++;
            }

        }

        return view('user.import')->with([
            'success' => $success,
            'fail' => $errors,
            'skip' => $skip
        ]);
    }

    public function export(){
        $csv = '"Email","Firstname","Lastname","Password","Role"'."\n";

        $users = User::query()->where('enabled', 1)->get();

        foreach($users as $u){
            $csv .= '"'.$u->email.'","'.$u->firstname.'","'.$u->lastname.'","N/A","'.$u->role.'"'."\n";
        }

        return response()->streamDownload(function() use($csv){
            echo mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
        },
        'Users.csv',
        ['Content-Type' => 'text/csv']);

    }
}
