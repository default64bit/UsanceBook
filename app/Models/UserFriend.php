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

    /*relation*/
    public function who(){
        return $this->belongsTo(User::class,'who');
    }
    public function whom(){
        return $this->belongsTo(User::class,'with_whom');
    }
    /*end relation*/
}
