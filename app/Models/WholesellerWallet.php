<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesellerWallet extends Model
{
    protected $table = 'wholeseller_wallets';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = ['credit', 'debit', 'user_id', 'payment_id', 'date', 'payment_mode'];

    protected $guarded = ['id'];
    
     public function user()
    {
        return $this->belongsToMany(Models\User::class,'user_id');
    }
     
    public function payment()
    {
        return $this->belongsTo(WholesellerPayment::class);
    }
}
