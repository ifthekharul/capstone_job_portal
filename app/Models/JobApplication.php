<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    public function job_details(){
        return $this->belongsTo(Job_details::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function employer(){
        return $this->belongsTo(User::class , 'employer_id');
    }
}
