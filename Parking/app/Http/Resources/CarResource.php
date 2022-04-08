<?php

namespace App\Http\Resources;

use App\Models\Space;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'matricula' => $this->matricula,
            'is_parked' => $this->isParked(),
            'space' => $this->space?->id,
            'space_created_at' => $this->space?->created_at,
            'spaces' => $this->when($this->space, new SpaceResource($this->space)),
        ];
    }
}
