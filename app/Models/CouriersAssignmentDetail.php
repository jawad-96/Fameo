<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouriersAssignmentDetail extends Model
{

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function couriers(){
        return $this->belongsTo(Courier::class,'couriers_id','id');
    }
}
