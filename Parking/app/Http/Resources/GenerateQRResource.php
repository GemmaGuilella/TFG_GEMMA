<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class GenerateQRResource extends JsonResource
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
            'qr' => Crypt::encryptString(json_encode(['id' => $this->id, 'token' => $this->token, 'token_created_at' => $this->token_created_at])),
        ];
    }
}
