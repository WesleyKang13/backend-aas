<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    public function daysFormat($day){
        foreach(['mon' => 'Monday','tue' => 'Tuesday','wed' => 'Wednesday','thu' => 'Thursday','fri' => 'Friday'] as $k => $v){
            if($day == $k){
                return $v;
            }
        }
    }
}
