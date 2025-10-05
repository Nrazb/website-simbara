<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type_id' => $this->type_id,
            'name' => $this->name,
            'detail' => $this->detail,
            'qty' => $this->qty,
            'reason' => $this->reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'type' => new TypeResource($this->whenLoaded('type')),
        ];
    }
}
