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

        return view('students.index');
    }

    public function create(){
        return view('students.create');
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
                'action' => '<a href="/studentcourse/'.$sc->id.'"
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

        return view('students.show')->with([
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

    public function createCourse($id){
        $student = Student::findOrFail($id);

        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->orderBy('name', 'asc')->get();

        $course = [null => 'Choose/select a course'];
        $class = [null => 'Choose/select a class'];

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' '.$c->year;
        }

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        return view('students.addcourse')->with([
            'student' => $student,
            'class' => $class,
            'course' => $course
        ]);
    }

    public function storeCourse($id){
        $rules = [];
        for($i = 1 ; $i < 4 ; $i++){
            if(isset($rules['course_'.$i])){
                $rules['class_'.$i] = 'required|exists:classrooms,id';
            }else{
                $rules['course_'.$i] = 'nullable|exists:courses,id';
                $rules['class_'.$i] = 'nullable|exists:classrooms,id';
    
            }
        }   

        $valid = request()->validate($rules);

        $student = Student::findOrFail($id);

        for($i = 1 ; $i < 4 ; $i++){
            if($valid['course_'.$i] !== null and $valid['class_'.$i] !== null){
                $course_id = $valid['course_'.$i];
                $class_id = $valid['class_'.$i];

                $course = Course::findOrFail($course_id);
                $class = Classroom::findOrFail($class_id);
            
                $student_course = new StudentCourse();
                $student_course->student_id = $student->id;
                $student_course->class_id = $class->id;
                $student_course->course_id = $course->id;
                $student_course->save();
            }
            
        }

        return redirect('/student/'.$student->id)->withSuccess('Course Added Successfully');
        
    }
}