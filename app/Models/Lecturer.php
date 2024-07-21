<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    public function color($enabled = null){
        if($enabled == 1){
            return 'success';
        }else{
            return 'danger';
        }
    }
}
