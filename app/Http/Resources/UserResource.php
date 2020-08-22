<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => ucfirst($this->name),
            'family' => ucfirst($this->family),
            'email' => $this->email,
            'register' => $this->created_at->diffForHumans(),
        ];
    }
}
