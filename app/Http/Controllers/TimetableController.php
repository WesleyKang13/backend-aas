<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use DateTime;
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
                ->addColumn('action', function($rows){
                    return '<a href="/timetable/'.$rows->id.'" class="btn btn-primary">Manage</a>';
                })
                ->rawColumns(['action','created_at', 'enabled'])
                ->make('true');
        }

        return view('timetable.index');
    }

    public function create(){
        $classes = Classroom::all();
        $courses = Course::query()->where('enabled', true)->get();

        $class = [null => 'Select a class'];
        $course = [null => 'Select a course'];

        foreach($classes as $c){
            $class[$c->id] = $c->code;
        }

        foreach($courses as $c){
            $course[$c->id] = $c->name. ' - (Year '.$c->year.')';
        }

        return view('timetable.create')->with([
            'courses' => $course,
            'classes' => $class
        ]);
    }

    public function store(){
        $valid = request()->validate([
            'class_id' => 'required|string|exists:classrooms,id',
            'course_id' => 'required|string|exists:courses,id',
            'name' => 'required|string|min:3',
            'from' => 'required|date',
            'to' => 'required|date'
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

        $entries = TimetableEntry::query()->where('timetable_id', $timetable->id)->get();

        if(request()->ajax()){
            return DataTables::of($entries)
                ->editColumn('day', function ($r){
                    return $r->daysFormat($r->day);
                })
                ->editColumn('created_at', function ($r){
                    return date('Y-m-d', strtotime($r->created_at));
                })
                ->addColumn('action', function($r){
                    return '<a href="/timetable_entry/'.$r->id.'/delete" class="btn btn-danger">Delete</a>';
                })
                ->rawColumns(['action', 'day'])
                ->make('true');
        }

        return view('timetable.show')->with('timetable', $timetable);
    }

    public function addschedule($id){
        // get timetable first
        $timetable = Timetable::findOrFail($id);

        $days = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday'
        ];

        return view('timetable.schedule.create')->with([
            'timetable' => $timetable,
            'days' => $days
        ]);
    }

    public function storeschedule($id){
        $valid = request()->validate([
            'day' => 'required|in:mon,tue,wed,thu,fri',
            'starttime' => 'required|date_format:H:i',
            'endtime' => 'required|date_format:H:i'
        ],[
            'day' => 'Day must be only from Monday to Friday',
            'starttime' => 'Invalid time format',
            'endtime' => 'Invalid time format'
        ]);

        $timetable = Timetable::findOrFail($id);

        $entry = new TimetableEntry();
        $entry->timetable_id = $timetable->id;
        foreach($valid as $k => $v){
            $entry->{$k} = $v;
        }

        $entry->save();

        return redirect('/timetable/'.$timetable->id)->withSuccess('Timetable entry created succesfully');
    }

    // delete entry
    public function delete($id){
        $entry = TimetableEntry::find($id);

        $timetable_id = $entry->timetable_id;
        if($entry == null){
            return back()->withError('Invalid. Schedule not found');
        }

        $entry->delete();

        return redirect('/timetable/'.$timetable_id)->withSuccess('Schedule successfully deleted');
    }
}
