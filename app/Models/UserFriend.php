<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'who', 'with_whom', 'accepted'
    ];

    const PER_PAGE = 30;

    /*relation*/
    public function user(){
        return $this->belongsTo(User::class,'who');
    }
    public function whom(){
        return $this->belongsTo(User::class,'with_whom');
    }
    /*end relation*/
}
