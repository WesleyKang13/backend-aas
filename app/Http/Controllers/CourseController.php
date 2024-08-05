<?php

namespace App\Http\Controllers;

use App\Models\Course;
use DataTables;
class CourseController extends Controller
{
    public function index(){
        if(request() -> ajax()){
            $rows = Course::query();

            return DataTables::of($rows)
                ->editColumn('enabled', function ($r){
                    if($r->enabled == 1){
                        return '<span class="badge bg-success">Yes</span>';
                    }else{
                        return '<span class="badge bg-danger">No</span>';
                    }
                })
                ->editColumn('created_at', function($r){
                    return date('Y-M-d', strtotime($r->created_at));
                })
                ->editColumn('updated_at', function($r){
                    return date('Y-M-d', strtotime($r->updated_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/course/'.$r->id.'" class="btn btn-primary btn-sm">Manage</a>';
                })
                ->rawColumns(['enabled','action'])
                ->make('true');
        }

        return view('course.index');
    }

    public function show($id){
        $course = Course::findOrFail($id);

        return view('course.show')->with('course', $course);
    }

    public function create(){
        return view('course.create');
    }

    public function store(){
        $valid = request()->validate([
            'name' => 'required|string',
            'code' => 'required|string|min:3',
            'total_student' => 'required|numeric',
            'year' => 'required'
        ]);

        $course = new Course();

        foreach($valid as $k => $v){
            $course->{$k} = $v;
        }

        $course->save();

        return redirect('/course/'.$course->id)->withSuccess('Course Created Successfully');
    }

    public function edit($id){
        $course = Course::findOrFail($id);

        $enabled = [
            1 => 'Yes',
            0 => 'No'
        ];

        return view('course.edit')->with([
            'course' => $course,
            'enabled' => $enabled
        ]);
    }

    public function update($id){
        $valid = request()->validate([
            'name' => 'required|string',
            'code' => 'required|string|min:3',
            'total_student' => 'required|string',
            'year' => 'required|string',
            'enabled' => 'required'
        ]);

        $course = Course::findOrFail($id);

        foreach($valid as $k => $v){
            $course->{$k} = $v;
        }

        $course->save();

        return redirect('/course/'.$course->id)->withSuccess('Course Updated Succesfully');
    }
}
