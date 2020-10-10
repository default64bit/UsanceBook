<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $for_user = [
            'value' => null,
            'name' => 'Nobody',
        ];
        if($this->whenLoaded('for_user') && $this->for_user){
            $for_user = [
                'value' => $this->for_user->id,
                'name' => $this->for_user->name.' '.$this->for_user->family,
            ];
        }

        $card = [
            'value' => null,
            'name' => 'No Card',
        ];
        if($this->whenLoaded('card') && $this->card){
            $card = [
                'value' => $this->card->id,
                'name' => $this->card->bank,
            ];
        }

        $transaction_groups = [];
        if($this->whenLoaded('groups') && $this->groups){
            foreach($this->groups as $transaction_group){
                $transaction_groups[$transaction_group->group->id] = [
                    'value' => $transaction_group->group->id,
                    'name' => $transaction_group->group->name,
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->title,
            'payed_by' => [
                'avatar' => $this->whenLoaded('user')->avatar,
                'name' => ucfirst($this->whenLoaded('user')->name) ,
                'family' => ucfirst($this->whenLoaded('user')->family),
            ],
            'amount' => number_format($this->amount),
            'raw_amount' => (string)$this->amount,
            'unit' => 'T',
            'type' => [
                'value' => $this->type,
                'name' => $this->type=='+' ? 'Gain' : 'Loss',
            ],
            'for_user' => $for_user,
            'transaction_groups' => $transaction_groups,
            'card' => $card,
            'date' => jdate($this->date)->format('Y, d F'),
            'raw_date' => Jalalian::forge($this->date)->format('%Y/%m/%d H:i:s'),
        ];
    }
}
