<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MutationItemRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'unit_confirmed' => $this->unit_confirmed,
            'recipient_confirmed' => $this->recipient_confirmed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'item' => new ItemResource($this->whenLoaded('item')),
            'from_user' => new UserResource($this->whenLoaded('fromUser')),
            'to_user' => new UserResource($this->whenLoaded('toUser')),
        ];
    }
}
