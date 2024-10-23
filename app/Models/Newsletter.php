<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids;

class Newsletter extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletters';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    public function newsletter_status()
    {
        return $this->hasMany(Newsletter_status::class);
    }

    protected static function boot ()
    {
        parent::boot();

        static::deleting(function($newsletter) {
            foreach ($newsletter->newsletter_status()->get() as $newsletter_status) {
                $newsletter_status->delete();                       
            }
        });
    }

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from', 'subject', 'content', 'status','start_date', 'end_date'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
