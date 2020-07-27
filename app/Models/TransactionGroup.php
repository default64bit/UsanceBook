<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'transaction_id'
    ];

    /*relation*/
    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class,'transaction_id');
    }
    /*end relation*/
}
