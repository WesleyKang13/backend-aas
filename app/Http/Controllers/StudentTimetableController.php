<?php

namespace App\Http\Controllers;

use App\Models\StudentTimetable;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use DataTables;

class StudentTimetableController extends Controller
{
    public function index($id){
        $student = Student::findOrFail($id);

        $timetables = StudentTimetable::query()->where('student_id', $student->id)->get();

        $details = [];

        foreach($timetables as $t){
            $timetable = Timetable::findOrFail($t->timetable_id);
            $classroom = Classroom::findOrFail($timetable->class_id);
            $course = Course::findOrFail($timetable->course_id);

            $details[$t->id] = [
                'student_timetable' => $t,
                'timetable_id' => '<a href="/timetable/'.$timetable->id.'">'.$timetable->id.'</a>',
                'class' => $classroom->code,
                'course' => $course->name,
                'year' => $course->year,
                'week_number' => $timetable->week_number,
                'day' => $timetable->day,
                'student_id' => $student->id
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
                            <a href="/studenttimetable/'.$r['student_timetable']->id.'/delete" class="btn btn-danger btn-sm">Delete</a>';
                })
                ->rawColumns(['action', 'timetable_id'])
                ->make('true');

        }

        return view('timetable.student.index')->with('student', $student);

    }

    public function delete($id){
        $student_timetable = StudentTimetable::findOrFail($id);

        $student_timetable->delete();

        return redirect('/student/'.$student_timetable->student_id.'/timetable')->withSuccess('Timetable Deleted Successfully');
    }

    public function create($student_id){
        $student = Student::findOrFail($student_id);
        $timetables = Timetable::query()->where('enabled', true)->get();

        $timetable = [null => 'Select/Choose a timetable'];

        foreach($timetables as $t){
            $timetable[$t->id] = '('.$t->course->name. ') - '.$t->classroom->code. ' | Week: '.$t->week_number. '/'.$t->year;
        }

        return view('timetable.student.create')->with([
            'timetable' => $timetable,
            'student' => $student
        ]);
    }

    public function store($student_id){
        $valid = request()->validate([
            'timetable_id' => 'required|exists:timetables,id'
        ]);

        $timetable = Timetable::findOrFail($valid['timetable_id']);

        $student = Student::findOrFail($student_id);

        $student_timetable = new StudentTimetable();
        $student_timetable->timetable_id = $timetable->id;
        $student_timetable->student_id = $student->id;
        $student_timetable->save();

        return redirect('/student/'.$student->id.'/timetable')->withSuccess('Timetable Created Successfully');
    }
}
