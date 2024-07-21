<?php

namespace App\Http\Controllers;

use App\Models\ClassCourse;
use App\Models\Course;
use App\Models\Classroom;
use DataTables;
class ClassroomController extends Controller
{
    public function index(){
        if(request()->ajax()){
            $rows = Classroom::query();

            return DataTables::of($rows)
                ->editColumn('created_at', function($r){
                    return date('Y-M-d H:i', strtotime($r->created_at));
                })
                ->editColumn('updated_at', function($r){
                    return date('Y-M-d H:i', strtotime($r->updated_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/classroom/'.$r->id.'" class="btn btn-primary btn-sm">Manage</a>';
                })
                ->rawColumns(['action'])
                ->make('true');

        }

        return view('classrooms.index');
    }

    public function show($id){
        $class = Classroom::findOrFail($id);

        $courses = ClassCourse::query()->where('class_id', $class->id)->orderBy('id', 'asc')->get();
        $class_courses = [];

        foreach($courses as $c){
            $course = Course::findOrFail($c->course_id);
            $class_courses[$c->course_id] = [
                'name' => '<a href="/course/'.$course->id.'">'.$course->name.'</a>',
                'year' => $course->year,
                'total_student' =>  $course->total_student,
                'created_at' => date('Y-M-d', strtotime($course->created_at)),
                'updated_at' => date('Y-M-d', strtotime($course->updated_at)),
                'action' => '<a href="/classcourse/'.$c->id.'/class/'.$class->id.'" class="btn btn-primary btn-sm">Manage</a>
                            <a href="/classcourse/'.$c->id.'/delete" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }

        if(request()->ajax()){
            $rows = $class_courses;

            return DataTables::of($rows)
                ->rawColumns(['name','action'])
                ->make('true');
        }

        return view('classrooms.show')->with([
            'class' => $class,
            'courses' => $class_courses
        ]);
    }

    public function create(){
        return view('classrooms.create');
    }

    public function store(){
        $valid = request()->validate([
            'code' => 'required|string'
        ]);

        $class = new Classroom();

        $class->code = $valid['code'];

        $class->save();

        return redirect('/classroom/'.$class->id)->withSuccess('Classroom Added Successfully');
    }

    public function update($id){
        $valid = request()->validate([
            'code' => 'required|string'
        ]);

        $class = Classroom::findOrFail($id);
        $class->code = $valid['code'];

        $class->save();

        return redirect('/classroom/'.$class->id)->withSuccess('Updated Successfully');
    }

}
