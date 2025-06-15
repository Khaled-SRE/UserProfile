<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileStoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
        ];
    }
}
