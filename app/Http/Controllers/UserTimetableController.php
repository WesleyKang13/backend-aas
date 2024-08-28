<?php

namespace App\Http\Controllers;

use App\Models\UserTimetable;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Timetable;
use DataTables;

class UserTimetableController extends Controller
{
    public function index($id){
        $user = User::findOrFail($id);

        $user_timetables = UserTimetable::query()->where('user_id', $user->id)->get();

        $user_timetable = [];

        foreach($user_timetables as $ut){
            $timetable = Timetable::findOrFail($ut->timetable_id);
            $class = Classroom::findOrFail($timetable->id);
            $course = Course::findOrFail($timetable->course_id);

            $user_timetable[] = [
                'timetable_id' => $ut->timetable_id,
                'class_code' => $class->code,
                'course_name' => $course->name,
                'course_year' => $course->year,
                'week_number' => $timetable->week_number,
                'day' => $timetable->day,
                'action' => '<a href="/users/'.$ut->id.'/timetable/delete" class="btn btn-danger btn-sm">Delete</a>'
            ];
        } 

        if(request()->ajax()){
            return DataTables::of($user_timetable)
                ->editColumn('timetable_id', function($r){
                    return $r['timetable_id'];
                })
                ->editColumn('class_code', function($r){
                    return $r['class_code'];
                })
                ->editColumn('course_name', function($r){
                    return $r['course_name'];
                })
                ->editColumn('course_year', function($r){
                    return $r['course_year'];
                })
                ->editColumn('week_number', function($r){
                    return $r['week_number'];
                })
                ->editColumn('day', function($r){
                    return $r['day'];
                })
                ->addColumn('action', function($r){
                    return $r['action'];
                })
                ->rawColumns(['action', 'timetable_id', 'week_number','day'])
                ->make('true');

        }

        return view('timetable.user.index')->with('user', $user);

    }

    public function delete($id){
        $user_timetable = UserTimetable::findOrFail($id);

        $user_timetable->delete();

        return redirect('/users/'.$user_timetable->user_id.'/timetable')->withSuccess('Timetable Deleted Successfully');
    }

    public function create($user_id){
        $user = User::findOrFail($user_id);
        $timetables = Timetable::query()->where('enabled', true)->get();

        $timetable = [null => 'Select/Choose a timetable'];

        foreach($timetables as $t){
            $timetable[$t->id] = '('.$t->course->name. ') - '.$t->classroom->code. ' | Week: '.$t->week_number. '/'.$t->year;
        }

        return view('timetable.user.create')->with([
            'timetable' => $timetable,
            'user' => $user
        ]);
    }

    public function store($user_id){
        $valid = request()->validate([
            'timetable_id' => 'required|exists:timetables,id'
        ]);

        $timetable = Timetable::findOrFail($valid['timetable_id']);

        $user = User::findOrFail($user_id);

        $user_timetable = new UserTimetable();
        $user_timetable->timetable_id = $timetable->id;
        $user_timetable->user_id = $user->id;
        $user_timetable->save();

        return redirect('/users/'.$user->id.'/timetable')->withSuccess('Timetable Created Successfully');
    }

    // public function edit($id){
    //     $user_timetable = UserTimetable::findOrFail($id);

    //     $timetables = Timetable::query()->where('enabled', true)->get();

    //     $timetable = [null => 'Select/Choose a timetable'];

    //     foreach($timetables as $t){
    //         $timetable[$t->id] = '('.$t->course->name. ') - '.$t->classroom->code. ' | Week: '.$t->week_number. '/'.$t->year;
    //     }

    //     return view('timetable.student.edit')->with([
    //         'timetable' => $timetable,
    //         'user_timetable' => $user_timetable
    //     ]);
    // }

    // public function update($id){
    //     $valid = request()->validate([
    //         'timetable_id' => 'required|exists:timetables,id'
    //     ]);

    //     $user_timetable = UserTimetable::findOrFail($id);

    //     $user_timetable->timetable_id = $valid['timetable_id'];

    //     $user_timetable->save();

    //     return redirect('/users/'.$user_timetable->user_id.'/timetable')->withSuccess('Timetable Updated Successfully');
    // }
}
