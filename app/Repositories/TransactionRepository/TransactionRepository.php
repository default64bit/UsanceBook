<?php

namespace App\Repositories\TransactionRepository;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    private $transaction;

    public function __construct(Transaction $transaction){
        $this->setModel($transaction);
        $this->transaction = $transaction;
    }

    public function create(array $data){
        $new_transaction = $this->transaction->create([
            'title' => $data['title'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'date' => $data['date'],
            'user_id' => $data['user_id'],
            'for_user_id' => $data['for_user_id'],
            'card_id' => $data['card_id'],
        ]);

        if(isset($data['transaction_group_id']) && !empty($data['transaction_group_id'])){
            $new_transaction->groups()->create([
                'group_id' => $data['transaction_group_id']
            ]);
        }

        return $new_transaction;
    }

    public function update($id){

    }

    public function read($id){
        return $this->transaction->where('id',$id)
            ->with(['user','card'])
            ->first();
    }

    public function all_transactions($search=null,$user_id,$per_page){
        $all_transactions = $this->transaction->where(function($query) use($user_id){
            $query->where('user_id',$user_id)->orWhere('for_user_id',$user_id);
        });

        if($search != null){
            $all_transactions = $this->search($all_transactions,$this->transaction::SEARCHABLE,$search)
                ->orWhereHas('card',function($query) use($search){
                    $query->where('bank','like',"%$search%");
                });
        }

        $all_transactions = $all_transactions->with(['user','card'])
            ->latest()
            ->paginate($per_page);

        return $all_transactions;
    }

    public function my_transactions($user_id,$per_page){
        return $this->transaction->where('user_id',$user_id)->whereNull('for_user_id')
            ->with(['user'])
            ->latest()
            ->paginate($per_page);
    }

    public function payed_by_me_for_others($user_id,$per_page){
        return $this->transaction->where('user_id',$user_id)->whereNotNull('for_user_id')
            ->with(['user'])
            ->latest()
            ->paginate($per_page);
    }

    public function others_pay_for_me($user_id,$per_page){
        return $this->transaction->where('user_id','=',$user_id)->where('for_user_id',$user_id)
            ->with(['user'])
            ->latest()
            ->paginate($per_page);
    }

}