<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank', 'number', 'user_id'
    ];

    const SEARCHABLE = [
        'bank', 'number'
    ];

    const PER_PAGE = 15;

    /*relation*/
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class,'card_id');
    }
    /*end relation*/
}
