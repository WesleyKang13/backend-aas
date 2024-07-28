<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use DataTables;
class TimetableController extends Controller{
    public function index(){

        if(request()->ajax()){
            $rows = Timetable::query();

            return DataTables::of($rows)
                ->editColumn('class_id', function($rows){
                    return $rows->classroom->code;
                })
                ->editColumn('course_id', function($rows){
                    return $rows->course->name;
                })
                ->editColumn('created_at', function($rows){
                    return date('Y-M-d', strtotime($rows->created_at));
                })
                ->editColumn('enabled', function($rows){
                    if($rows->enabled == 1){
                        return '<span class="badge bg-success">Yes</span>';
                    }else{
                        return '<span class="badge bg->danger">No</span>';
                    }
                })
                ->addColumn('action', function($rows){
                    return '<a href="/timetable/'.$rows->id.'" class="btn btn-primary">Manage</a>';
                })
                ->rawColumns(['action','created_at', 'enabled'])
                ->make('true');
        }

        return view('timetables.index');
    }

    public function create(){
        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->get();

        $days = [
            null => 'Select a day',
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ];

        $class = [null => 'Select a class'];
        $course = [null => 'Select a course'];

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' - (Year '.$c->year.')';
        }

        return view('timetables.create')->with([
            'days' => $days,
            'courses' => $course,
            'classes' => $class
        ]);
    }

    public function store(){
        $valid = request()->validate([
            'class_id' => 'required|string|exists:classrooms,id',
            'course_id' => 'required|string|exists:courses,id',
            'week_number' => 'required|numeric|min:1|max:52',
            'day' => 'required|string',
            'year' => 'required|numeric',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ], [
            'start_time.date_format' => 'Invalid start time',
            'end_time.date_format' => 'Invalid end time'
        ]);

        $timetable = new Timetable();

        foreach($valid as $k => $v){
            $timetable->{$k} = $v;
        }

        $timetable->save();

        return redirect('/timetable/'.$timetable->id)->withSuccess('Timetable Created Successfully');
    }

    public function show($id){
        $timetable = Timetable::findOrFail($id);

        return view('timetables.show')->with('timetable', $timetable);
    }
}
