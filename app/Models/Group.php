<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'owner_id', 'who_can_see', 'who_can_pay'
    ];

    const WHO_CAN_SEE = [
        'everyone' => 'Everyone',
        'friends' => 'Friends',
        'only_me' => 'Only Me',
    ];
    const WHO_CAN_PAY = [
        'everyone' => 'Everyone',
        'friends' => 'Friends',
        'only_me' => 'Only Me',
    ];

    const SEARCHABLE = [
        'name',
    ];

    const PER_PAGE = 15;

    /*relation*/
    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }
    public function transactions(){
        return $this->hasMany(TransactionGroup::class,'group_id');
    }
    /*end relation*/
}
