<?php

namespace App\Http\Controllers;

use App\Models\StudentTimetable;
use App\Models\Student;
use DataTables;

class StudentTimetableController extends Controller
{
    public function index($id){
        $student = Student::findOrFail($id);

        $timetables = StudentTimetable::query()->where('student_id', $student->id);

        if(request()->ajax()){
            return DataTables::of($timetables)
                ->editColumn('class_id', function($r){
                    return $r->class->code;
                })
                ->editColumn('course_id', function($r){
                    return $r->course->name;
                })
                ->addColumn('action', function($r){
                    return '<a href="/student/'.$r->student_id.'/timetable/'.$r->id.'" class="btn btn-primary btn-sm">Manage</a>';
                })
                ->rawColumns(['action'])
                ->make('true');

        }

        return view('timetable.student.index');



    }
}
