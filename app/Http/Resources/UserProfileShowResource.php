<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileShowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'locale' => $this->locale,
            'profile_image' => $this->profile_image_url,
        ];
    }
}
