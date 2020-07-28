<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
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

    public function getFriendsAttribute(){
        return $this->my_friends->merge($this->other_friends);
    }

    /*relation*/
    public function transactions(){
        return $this->hasMany(Transaction::class,'user_id');
    }
    public function for_transactions(){
        return $this->hasMany(Transaction::class,'for_user_id');
    }
    public function my_friends(){
        return $this->hasMany(UserFriend::class,'who');
    }
    public function other_friends(){
        return $this->hasMany(UserFriend::class,'with_whom');
    }
    public function groups(){
        return $this->hasMany(Group::class,'owner_id');
    }
    public function cards(){
        return $this->hasMany(Card::class,'user_id');
    }
    /*end relation*/

    public function findForPassport($username){
        return $this->where('email',$username)->first();
    }
    public function validateForPassportPasswordGrant($password){
        return Hash::check($password,$this->password);
    }
}
