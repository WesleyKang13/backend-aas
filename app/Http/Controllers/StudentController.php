<?php

namespace App\Http\Controllers;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Course;
use App\Models\StudentCourse;
use DataTables;

class StudentController extends Controller{
    public function index(){
        if(request()->ajax()){
            $rows = Student::query();

            return DataTables::of($rows)
                ->editColumn('enabled', function($r){
                    if($r->enabled == 1){
                        return '<span class="badge bg-success">Yes</span>';
                    }else{
                        return '<span class="badge bg-danger">No</span>';
                    }
                })
                ->editColumn('created_at', function($r){
                    return date('Y-M-d', strtotime($r->created_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/student/'.$r->id.'" class="btn btn-primary">Manage</a>';
                })
                ->rawColumns(['action','enabled'])
                ->make('true');

        }

        return view('student.index');
    }

    public function create(){
        return view('student.create');
    }

    public function store(){
        $valid = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string'
        ]);

        $student = new Student();
        $student->firstname = $valid['firstname'];
        $student->lastname = $valid['lastname'];

        $student->save();

        return redirect('/student/'.$student->id)->withSuccess('Student Created Succesffully');

    }

    public function show($id){
        $student = Student::findOrfail($id);

        $student_course = StudentCourse::query()->where('student_id', $student->id)->get();

        $student_details = [];

        foreach($student_course as $sc){
            $class = Classroom::findOrFail($sc->class_id);
            $course = Course::findOrFail($sc->course_id);

            $student_details[$sc->course_id] = [
                'code' => $class->code,
                'year' => $course->year,
                'name' => '<a href="/course/'.$course->id.'">'.$course->name.'</a>',
                'created_at' => date('Y-M-d H:i', strtotime($sc->created_at)),
                'updated_at' => date('Y-M-d H:i', strtotime($sc->updated_at)),
                'action' => '<a href="/studentcourse/'.$sc->id.'/edit"
                                class="btn btn-primary btn-sm">Manage</a>
                            <a href="/studentcourse/'.$sc->id.'/delete" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }

        if(request()->ajax()){
            $rows = $student_details;

            return DataTables::of($rows)
                ->rawColumns(['name', 'action'])
                ->make('true');
        }

        return view('student.show')->with([
            'student' => $student
        ]);
    }

    public function update($id){
        $valid = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string'
        ]);

        $student = Student::findOrFail($id);

        foreach($valid as $k => $v){
            $student->{$k} = $v;
        }

        $student->save();

        return redirect('/student/'.$student->id)->withSuccess('Student Updated Successfully');

    }

    public function status($id){
        $status = request()->get('status');

        $valid_status = ['enabled','disabled'];

        if(!in_array($status, $valid_status)){
            return back()->withError('Invalid Status');
        }

        $student = Student::findOrFail($id);

        if($status == 'enabled'){
            $status = 1;
            if($student->enabled == 1){
                return back()->withError('Student is Enabled');
            }

            $student->enabled = $status;

        }else{
            $status = 0;

            if($student->enabled == 0){
                return back()->withError('Student is Disabled');
            }

            $student->enabled = $status;
        }

        $student->save();

        return redirect('/student/'.$student->id)->withSuccess('Status Updated');
    }


}
