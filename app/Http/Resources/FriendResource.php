<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();
        if($this->user->id != $user->id){
            $friend = $this->user;
        }else{ $friend = $this->whom; }

        return [
            'id' => $friend->id,
            'avatar' => $friend->avatar,
            'name' => ucfirst($friend->name),
            'family' => ucfirst($friend->family),
            'email' => $friend->email,
            'friendship_date' => $this->created_at->diffForHumans(),
            'accepted' => $this->accepted ? true : false,
        ];
    }
}
