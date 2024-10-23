<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter_status extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_status';


    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'newsletter_id', 'subscriber_id', 'status', 'send_time'
    ];

}
