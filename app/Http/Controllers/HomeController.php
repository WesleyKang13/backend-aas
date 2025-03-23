<?php

namespace App\Http\Controllers;
use App\Models\Week;
use App\Models\Timetable;
use App\Models\Attendance;
use App\Models\TimetableEntry;
use App\Models\UserTimetable;

class HomeController extends Controller{
    public function dashboard($month, $day){

        $date = date('Y-'.$month.'-'.$day);
        // get students attendance
        $students_attendance = [];
        $today_count = 0;
        $total_count = 0;

        $students  = Attendance::query()->where('date', $date)->get();

        foreach($students as $s){
            if($s->user->role == 'student'){
                $students_attendance[$s->course->name][] = [
                    'student_id' => $s->user->id
                ];
            }
        }
        
        // Prepare student attendance data for chart
        $student_attendance_chart = [];
        foreach ($students_attendance as $course => $attendance) {
            $student_attendance_chart[] = [
                'course' => $course,
                'count' => count($attendance),
            ];
            $today_count += count($attendance);
        }

        // get total attendance to be submitted today
        $total_attendance = [];

        $timetables = Timetable::query()->where('from', '<=', $date)->where('to', '>=', $date)->get();

        foreach($timetables as $t){
            $entries = TimetableEntry::query()
                    ->where('day', lcfirst(date('D', strtotime($date))))
                    ->where('timetable_id', $t->id)
                    ->get();

            // check how many students are having this timetable and add it total
            foreach($entries as $e){
                $users_timetable = UserTimetable::query()
                        ->join('users', 'users.id', 'users_timetables.user_id')
                        ->where('users.role', 'student')
                        ->where('users_timetables.timetable_id', $e->timetable_id)
                        ->count();

                $timetable = Timetable::findOrFail($e->timetable_id);

                $total_attendance[$timetable->id. ' '.$timetable->course->name] = $users_timetable;
            }
        }

        // Prepare total attendance data for chart
        $total_attendance_chart = [];

        foreach ($total_attendance as $course_name => $count) {
            $total_attendance_chart[] = [
                'course' => substr($course_name, 2), //  substr can be removed ( if course wont be having twice on the same day )
                'count' => $count
            ];
            $total_count += $count;
        }

        return view('dashboard')->with([
            'student_attendance_chart' => $student_attendance_chart,
            'total_attendance_chart' => $total_attendance_chart,
            'today_count' => $today_count,
            'total_count' => $total_count
        ]);
    }

}

