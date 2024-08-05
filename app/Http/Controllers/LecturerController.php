<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\LecturerCourse;
use App\Models\Classroom;
use App\Models\Course;
use DataTables;

class LecturerController extends Controller{
    public function index(){
        if(request()->ajax()){
            $rows = Lecturer::query();

            return Datatables::of($rows)
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
                    return '<a href="/lecturer/'.$r->id.'" class="btn btn-primary">Manage</a>';
                })
                ->rawColumns(['action','enabled'])
                ->make('true');
        }

        return view('lecturer.index');
    }

    public function create(){
        return view('lecturer.create');
    }

    public function store(){
        $valid = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string'
        ]);

        $lecturer = new Lecturer();
        $lecturer->firstname = $valid['firstname'];
        $lecturer->lastname = $valid['lastname'];

        $lecturer->save();

        return redirect('/lecturer/'.$lecturer->id)->withSuccess('lecturer Created Succesffully');

    }

    public function show($id){
        $lecturer = Lecturer::findOrfail($id);

        $lecturer_course = LecturerCourse::query()->where('lecturer_id', $lecturer->id)->get();

        $lecturer_details = [];

        foreach($lecturer_course as $lc){
            $class = Classroom::findOrFail($lc->class_id);
            $course = Course::findOrFail($lc->course_id);

            $lecturer_details[$lc->course_id] = [
                'code' => $class->code,
                'year' => $course->year,
                'name' => '<a href="/course/'.$course->id.'">'.'('.$course->code.') '.$course->name.'</a>',
                'created_at' => date('Y-M-d H:i', strtotime($lc->created_at)),
                'updated_at' => date('Y-M-d H:i', strtotime($lc->updated_at)),
                'action' => '<a href="/lecturercourse/'.$lc->id.'/edit"
                                class="btn btn-primary btn-sm">Manage</a>
                            <a href="/lecturercourse/'.$lc->id.'/delete" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }

        if(request()->ajax()){
            $rows = $lecturer_details;

            return DataTables::of($rows)
                ->rawColumns(['name', 'action'])
                ->make('true');
        }

        return view('lecturer.show')->with([
            'lecturer' => $lecturer
        ]);
    }

    public function status($id){
        $status = request()->get('status');

        $valid_status = ['enabled','disabled'];

        if(!in_array($status, $valid_status)){
            return back()->withError('Invalid Status');
        }

        $lecturer = Lecturer::findOrFail($id);

        if($status == 'enabled'){
            $status = 1;
            if($lecturer->enabled == 1){
                return back()->withError('lecturer is Enabled');
            }

            $lecturer->enabled = $status;

        }else{
            $status = 0;

            if($lecturer->enabled == 0){
                return back()->withError('lecturer is Disabled');
            }

            $lecturer->enabled = $status;
        }

        $lecturer->save();

        return redirect('/lecturer/'.$lecturer->id)->withSuccess('Status Updated');
    }

}
