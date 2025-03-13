<?php

namespace App\Http\Controllers;

use App\Models\UserTimetable;
use App\Models\TimetableEntry;
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

        $alreadyAssigned = UserTimetable::where('timetable_id', $timetable->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyAssigned) {
            return redirect('/users/' . $user->id)->withError('This is already assigned');
        }

        // check if assigned timetable is having the same course on the same day
        $timetables = Timetable::where('course_id', $timetable->course_id)
                    ->with('entries')
                    ->get();

        $days = [];
        foreach ($timetables as $t) {
            $days[$t->id] = $t->entries->pluck('day')->toArray();
        }

        $requestedEntries = TimetableEntry::where('timetable_id', $valid['timetable_id'])
                ->pluck('day')
                ->toArray();

        foreach ($timetables as $t) {
            // check if exists
            $exists = UserTimetable::where('timetable_id', $t->id)->where('user_id', $user->id)->exists();

            if ($exists) {
                foreach ($requestedEntries as $day) {
                    if (!empty($days[$t->id]) and in_array($day, $days[$t->id])) {
                        return redirect('/users/' . $user->id)->withError(
                            'You cannot be assigned to a timetable with the same course on the same day - ' . ucfirst($day)
                        );
                    }
                }
            }
        }

        $user_timetable = new UserTimetable();
        $user_timetable->timetable_id = $timetable->id;
        $user_timetable->user_id = $user->id;
        $user_timetable->save();

        $course = Course::query()->where('id', $timetable->course_id)->first();
        $course->total_student += 1;
        $course->save();

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
