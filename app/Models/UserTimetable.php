<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTimetable extends Model
{
    protected $table = 'users_timetables';
    use HasFactory;

    public function timetable(){
        return $this->belongsTo(Timetable::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
