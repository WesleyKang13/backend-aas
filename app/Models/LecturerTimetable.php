<?php

namespace App\Models;

use App\Models\Timetable;
use App\Models\Lecturers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerTimetable extends Model
{
    use HasFactory;


    public function timetable(){
        return $this->belongsTo(Timetable::class);
    }

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }
}
