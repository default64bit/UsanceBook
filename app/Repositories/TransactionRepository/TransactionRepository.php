<?php

namespace App\Repositories\TransactionRepository;

use App\Filters\CardFilter;
use App\Filters\GroupFilter;
use App\Models\Transaction;
use App\Models\TransactionGroup;
use App\Repositories\BaseRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    private $transaction;

    public function __construct(Transaction $transaction){
        $this->setModel($transaction);
        $this->transaction = $transaction;
    }

    public function check_user($user_id,$id){
        $user_can_access = $this->transaction->where('id',$id)->where('user_id',$user_id)->exists();
        if(!$user_can_access){ abort(404); }
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

        if(isset($data['transaction_groups']) && !empty($data['transaction_groups'])){
            $transaction_groups = json_decode($data['transaction_groups'],true);
            $insert = [];
            foreach($transaction_groups as $g_id => $transaction_group){
                $insert[] = [
                    'group_id' => $transaction_group['value'],
                    'transaction_id' => $new_transaction->id,
                    'created_at' => date('Y/m/d H:i:s'),
                    'updated_at' => date('Y/m/d H:i:s'),
                ];
            }
            TransactionGroup::insert($insert);
        }

        return $new_transaction;
    }

    public function update(array $data,$id){
        $transaction = $this->transaction->findOrFail($id);
        $transaction->update([
            'title' => $data['title'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'date' => $data['date'],
            'user_id' => $data['user_id'],
            'for_user_id' => $data['for_user_id'],
            'card_id' => $data['card_id'],
        ]);

        if(isset($data['transaction_groups']) && !empty($data['transaction_groups'])){
            $transaction_groups = json_decode($data['transaction_groups'],true);
            $transaction->groups()->delete();
            $insert = [];
            foreach($transaction_groups as $g_id => $transaction_group){
                $insert[] = [
                    'group_id' => $transaction_group['value'],
                    'transaction_id' => $transaction->id,
                    'created_at' => date('Y/m/d H:i:s'),
                    'updated_at' => date('Y/m/d H:i:s'),
                ];
            }
            TransactionGroup::insert($insert);
        }

        return $transaction;
    }

    public function read($id){
        return $this->transaction->where('id',$id)
            ->with(['user','card','groups.group','for_user'])
            ->first();
    }

    public function top_transactions($user_id){
        $transaction = $this->transaction->where(function($query) use($user_id){
            $query->where('user_id',$user_id)->orWhere('for_user_id',$user_id);
        })->with(['user','card']);
        
        $positive_total = with(clone $transaction)->where('type','+')->count();
        $negative_total = with(clone $transaction)->where('type','-')->count();
        $total = $positive_total + $negative_total;

        $top_positive = with(clone $transaction)->where('type','+')->orderBy('amount','desc')->first();
        $top_negative = with(clone $transaction)->where('type','-')->orderBy('amount','desc')->first();
        
        return [
            'total_number_of_transactions' => $total,
            'total_positive_transactions' => $positive_total,
            'total_negative_transactions' => $negative_total,
            'top_positive' => $top_positive,
            'top_negative' => $top_negative,
        ];
    }

    public function all_transactions($filters=[],$user_id,$per_page){
        $all_transactions = $this->transaction->where(function($query) use($user_id){
            $query->where('user_id',$user_id)->orWhere('for_user_id',$user_id);
        });


        if(isset($filters['search']) && !empty($filters['search'])){
            $search = $filters['search'];
            $all_transactions = $this->search($all_transactions,$this->transaction::SEARCHABLE,$search)
                ->orWhereHas('card',function($query) use($search){
                    $query->where('bank','like',"%$search%");
                });
        }
        $all_transactions = app(Pipeline::class)
            ->send($all_transactions)
            ->through([
                CardFilter::class,
                GroupFilter::class,
            ])->thenReturn();

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

    public function transactions_payed_by_me_for_others($user_id,$per_page){
        return $this->transaction->where('user_id',$user_id)->whereNotNull('for_user_id')
            ->with(['user'])
            ->latest()
            ->paginate($per_page);
    }

    public function transactions_payed_by_others_for_me($user_id,$per_page){
        return $this->transaction->where('user_id','=',$user_id)->where('for_user_id',$user_id)
            ->with(['user'])
            ->latest()
            ->paginate($per_page);
    }

}