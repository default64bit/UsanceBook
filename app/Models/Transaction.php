<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'amount', 'type', 'date', 'user_id', 'for_user_id', 'card_id'
    ];

    /*relation*/
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function for_user(){
        return $this->hasMany(User::class,'for_user_id');
    }
    public function groups(){
        return $this->hasMany(TransactionGroup::class,'transaction_id');
    }
    public function card(){
        return $this->belongsTo(Card::class,'card_id');
    }
    /*end relation*/
}
