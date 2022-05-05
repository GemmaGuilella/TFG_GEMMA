<?php

namespace App\Http\Resources;

use App\Models\Space;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

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
            'qr_token' => $this->when($this->token, Crypt::encryptString(json_encode(['id' => $this->id, 'token' => $this->token, 'token_created_at' => $this->token_created_at]))),
            'space' => $this->when($this->space, new SpaceResource($this->space)),
        ];
    }
}
