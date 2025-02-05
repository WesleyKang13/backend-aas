<?php

namespace App\Http\Controllers;
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
}
