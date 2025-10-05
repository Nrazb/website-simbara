<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceItemRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'item_id' => $this->item_id,
            'item_status' => $this->item_status,
            'information' => $this->information,
            'request_status' => $this->request_status,
            'unit_confirmed' => $this->unit_confirmed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'item' => new ItemResource($this->whenLoaded('item')),
        ];
    }
}
