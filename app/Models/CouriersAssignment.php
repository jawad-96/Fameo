<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouriersAssignment extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }


    public function detail(){
        return $this->hasMany(CouriersAssignmentDetail::class,'couriers_assignment_id','id')->with('product');
    }
}
