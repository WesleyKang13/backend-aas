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

    public function delete($id){
        $user_timetable = UserTimetable::findOrFail($id);

        $user_timetable->delete();

        return redirect('/users/'.$user_timetable->user_id)->withSuccess('Timetable Deleted Successfully');
    }

    public function create($user_id){
        $user = User::findOrFail($user_id);
        $timetables = Timetable::all();

        $timetable = [null => 'Select/Choose a timetable'];

        foreach($timetables as $t){
            $timetable[$t->id] = '('.$t->course->name. ') - '.$t->classroom->code. ' | From: '.$t->from. ' - '.$t->to .' / Note:'.$t->name;
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

        return redirect('/users/'.$user->id)->withSuccess('Timetable Created Successfully');
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
