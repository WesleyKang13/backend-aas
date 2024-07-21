<?php

namespace App\Http\Controllers;
use App\Models\Week;
use App\Models\Timetable;

class HomeController extends Controller{
    public function index(){
        // $current_week = date('W');

        // $weeks = Week::query()->where('enabled', true)->where('week_number', $current_week)->get();

        // $timetables = [];

        // foreach($weeks as $w){
        //     $timetables[$w->id] = Timetable::query()
        //                         ->where('timetables.week_id', $w->id)
        //                         ->where('timetables.enabled', true)
        //                         ->select('timetables.*', 'classes.code as code')
        //                         ->join('classes', 'classes.id', '=', 'timetables.class_id')
        //                         ->get();
        // }

        return view('dashboard');
    }
}

