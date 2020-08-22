<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'name' => $this->title,
            'payed_by' => [
                'avatar' => $this->whenLoaded('user')->avatar,
                'name' => ucfirst($this->whenLoaded('user')->name) ,
                'family' => ucfirst($this->whenLoaded('user')->family),
            ],
            'amount' => number_format($this->amount),
            'unit' => 'T',
            'type' => $this->type,
            'card' => $this->whenLoaded('card') ? $this->whenLoaded('card')->bank : 'Wallet',
            'date' => date('d M,Y',strtotime($this->date)),
        ];
    }
}
