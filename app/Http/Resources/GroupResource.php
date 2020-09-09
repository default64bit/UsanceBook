<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $total = 0;
        $max_payers = 5;
        $other_payers = [];

        if($this->whenLoaded('transactions')){
            foreach($this->transactions as $transaction_group){
                if($transaction_group->transaction->type == '+'){
                    $total += $transaction_group->transaction->amount;
                }else{ $total -= $transaction_group->transaction->amount; }

                if($this->whenLoaded('transactions.user')){
                    if($transaction_group->transaction->user->id != $this->owner_id && $max_payers > 0){
                        $other_payers[] = [
                            'avatar' => $transaction_group->transaction->user->avatar,
                            'name' => $transaction_group->transaction->user->name,
                            'family' => $transaction_group->transaction->user->family,
                        ];
                    }
                }
            }
        }

        $type = $total>0 ? '+' : '-';
        if($total == 0){ $type = ''; }
        $total = str_replace('-','',$total);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'who_can_see' => Group::WHO_CAN_SEE[$this->who_can_see],
            'who_can_pay' => Group::WHO_CAN_PAY[$this->who_can_pay],
            'user' => new UserResource($this->whenLoaded('owner')),

            'total' => number_format($total),
            'type' => $type,
            'unit' => 'T',
            'other_payers' => $other_payers,
        ];
    }
}
