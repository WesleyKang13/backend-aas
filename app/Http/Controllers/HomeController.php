<?php

namespace App\Http\Controllers;
use App\Models\Week;
use App\Models\Timetable;

class HomeController extends Controller{
    public function dashboard(){


        return view('dashboard');
    }
}

