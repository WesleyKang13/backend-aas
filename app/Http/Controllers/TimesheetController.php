<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use DataTables;
class TimesheetController extends Controller{
    public function calendar(){

        // get today's date
        $month = request()->get('month');

        $today = date('Y-'.$month.'-d');
        return view('timesheet.calendar')
                ->with([
                    'today' => $today
                ]);
    }

    public function list($month, $day){

        $date_to_list = date('Y-'.$month.'-'.$day);

        if($month > 12 || $month < 1){
            return redirect('/timesheet?month='.date('m'))->withError('Invalid month');
        }

        $days = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

        if($day > $days || $day < 1){
            return redirect('/timesheet?month='.$month)->withError('Invalid day');
        }

        $date = date('Y-m-d', strtotime($date_to_list));

        if(request()->ajax()){
            $rows = Attendance::query()
                ->join('users', 'users.id', 'attendance.user_id')
                ->join('courses', 'courses.id', 'attendance.course_id')
                ->select('attendance.*', 'users.firstname as firstname', 'users.lastname as lastname', 'courses.name as course', 'users.role as role')
                ->where('attendance.date',$date);

            return DataTables::of($rows)
                        ->editColumn('users.firstname', function($r){
                            return $r->firstname;
                        })
                        ->editColumn('users.lastname', function($r){
                            return $r->lastname;
                        })
                        ->editColumn('courses.name', function($r){
                            return $r->course;
                        })
                        ->editColumn('users.role', function($r){
                            return $r->role;
                        })
                        ->addColumn('action', function($r){
                            return '<a href="#" class="btn btn-primary">View</a>';
                        })
                        ->rawColumns(['action','firstname'])
                        ->make('true');
        }

        return view('timesheet.list')->with([
            'date' => $date
        ]);

    }
}
