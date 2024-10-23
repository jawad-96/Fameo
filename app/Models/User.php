<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * belongs To relation Store_products
     */

    public function transactions()
    {
        return $this->hasMany(Models\Transaction::class, 'user_id');
    }

    public function getFullNameAttribute($value)
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    public function getFullNameWithEmailAttribute($value)
    {
        return $this->first_name.' '.$this->last_name.' ('.$this->email.' - '. ucwords($this->type).')' ;
    }
    
 public function getAmountSumAttribute(){
   
    return $this->transactions->sum('amount');
}


 public function scopeDropship($query)
    {
        return $query->whereType('dropshipper');
    }

    public function scopeWhole($query)
    {
        return $query->whereType('wholesaler');
    }
    
        public function shoppings()
    {
       return  $this->hasMany(Models\ShoppingCart::class);
    }
    
      public function wallets()
    {
        return $this->hasMany(Models\WholesellerWallet::class,'user_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','phone', 'type','address', 'max_limit'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
