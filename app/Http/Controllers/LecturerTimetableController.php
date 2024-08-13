<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\LecturerTimetable;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use DataTables;

class LecturerTimetableController extends Controller{
    public function index($id){
        $lecturer = Lecturer::findOrFail($id);

        $lecturer_timetables = LecturerTimetable::query()->where('lecturer_id', $lecturer->id)->get();

        $details = [];

        foreach($lecturer_timetables as $t){
            $timetable = Timetable::findOrFail($t->timetable_id);
            $classroom = Classroom::findOrFail($timetable->class_id);
            $course = Course::findOrFail($timetable->course_id);

            $details[$t->id] = [
                'lecturer_timetable' => $t,
                'timetable_id' => '<a href="/timetable/'.$timetable->id.'">'.$timetable->id.'</a>',
                'class' => $classroom->code,
                'course' => $course->name,
                'year' => $course->year,
                'week_number' => $timetable->week_number,
                'day' => $timetable->day,
                'student_id' => $lecturer->id
            ];
        }


        if(request()->ajax()){
            return DataTables::of($details)
                ->editColumn('class', function($r){
                    return $r['class'];
                })
                ->editColumn('course', function($r){
                    return $r['course'];
                })
                ->editColumn('year', function($r){
                    return $r['year'];
                })
                ->editColumn('week_number', function($r){
                    return $r['week_number'];
                })
                ->editColumn('day', function($r){
                    return $r['day'];
                })
                ->addColumn('action', function($r){
                    return '<a href="" class="btn btn-primary btn-sm">Edit</a>
                            <a href="/lecturertimetable/'.$r['lecturer_timetable']->id.'/delete" class="btn btn-danger btn-sm">Delete</a>';
                })
                ->rawColumns(['action', 'timetable_id'])
                ->make('true');

        }

        return view('timetable.lecturer.index')->with('lecturer', $lecturer);

    }

    public function create($id){
        $lecturer = Lecturer::findOrFail($id);
        $timetables = Timetable::query()->where('enabled', true)->get();

        $timetable = [null => 'Select/Choose a timetable'];

        foreach($timetables as $t){
            $timetable[$t->id] = '('.$t->course->name. ') - '.$t->classroom->code. ' | Week: '.$t->week_number. '/'.$t->year;
        }

        return view('timetable.lecturer.create')->with([
            'timetable' => $timetable,
            'lecturer' => $lecturer
        ]);

    }

    public function store($id){
        $valid = request()->validate([
            'timetable_id' => 'required|exists:timetables,id'
        ]);

        $lecturer = Lecturer::findOrFail($id);

        $new = new LecturerTimetable();
        $new->lecturer_id = $lecturer->id;
        $new->timetable_id = $valid['timetable_id'];
        $new->save();

        return redirect('/lecturer/'.$lecturer->id.'/timetable')->withSuccess('Timetable Added Succesfully');
    }

    public function delete($id){
        $lecturer_timetable = LecturerTimetable::findOrFail($id);

        $lecturer_timetable->delete();

        return redirect('/lecturer/'.$lecturer_timetable->id.'/timetable')->withSuccess('Timetable Deleted Successfully');
    }
}
