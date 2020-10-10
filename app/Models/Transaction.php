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

    const SEARCHABLE = [
        'title', 'amount', 'date'
    ];
    
    const PER_PAGE = 15;

    /*relation*/
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function for_user(){
        return $this->belongsTo(User::class,'for_user_id');
    }
    public function groups(){
        return $this->hasMany(TransactionGroup::class,'transaction_id');
    }
    public function card(){
        return $this->belongsTo(Card::class,'card_id');
    }
    /*end relation*/
}
