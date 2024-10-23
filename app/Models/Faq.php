<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'answer','ordering'
    ];
    
    
    
}
