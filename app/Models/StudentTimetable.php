<?php

namespace App\Models;

use App\Models\Timetable;
use App\Models\Student;
use App\Models\Course;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTimetable extends Model
{
    use HasFactory;


    public function timetable(){
        return $this->belongsTo(Timetable::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
