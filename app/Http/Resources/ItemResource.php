<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type_id' => $this->type_id,
            'code' => $this->code,
            'order_number' => $this->order_number,
            'name' => $this->name,
            'cost' => $this->cost,
            'acquisition_date' => $this->acquisition_date,
            'acquisition_year' => $this->acquisition_year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'type' => new TypeResource($this->whenLoaded('type')),
        ];
    }
}
