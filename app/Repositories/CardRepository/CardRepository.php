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
        
    }

    public function update(array $data,$id){
        
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

}