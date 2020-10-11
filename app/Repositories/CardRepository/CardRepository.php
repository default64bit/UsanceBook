<?php

namespace App\Repositories\CardRepository;

use App\Models\Card;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{
    private $card;

    public function __construct(Card $card){
        $this->setModel($card);
        $this->card = $card;
    }

    public function check_user($user_id,$id){
        $user_can_access = $this->card->where('id',$id)->where('user_id',$user_id)->exists();
        if(!$user_can_access){ abort(404); }
    }

    public function create(array $data){
        $new_card = $this->card->create([
            'bank' => $data['bank'],
            'number' => $data['number'],
            'user_id' => $data['user_id'],
        ]);
        return $new_card;
    }

    public function update(array $data,$id){
        $card = $this->card->findOrFail($id);
        $card->update([
            'bank' => $data['bank'],
            'number' => $data['number'],
        ]);
        return $card;
    }

    public function read($id){
        return $this->card->where('id',$id)
            ->with(['user'])
            ->first();
    }

    public function all_cards($search=null,$user_id,$per_page){
        $all_cards = $this->card->where('user_id',$user_id);

        if($search != null){
            $all_cards = $this->search($all_cards,$this->card::SEARCHABLE,$search);
        }

        $all_cards = $all_cards->with(['user'])->latest();
        if($per_page != 'all'){
            $all_cards = $all_cards->paginate($per_page);
        }else{ $all_cards = $all_cards->get(); }

        return $all_cards;
    }

    public function getStatistics($card_id,$user,$period,$from_date=null,$to_date=null){
        $card = $this->card->where('id',$card_id)->where('user_id',$user->id)->first();
        $card_transactions = $card->transactions();

        if($from_date || $to_date){
            if($from_date){ $card_transactions->where('date','>=',$from_date); }
            if($to_date){ $card_transactions->where('date','<=',$to_date); }
        }
        
        $card_transactions = $card_transactions->get();
        return $card_transactions;
    }

}