<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'profile_image',
        'gender',
        'phone',
        'locale',
    ];

    public function getProfileImageUrlAttribute(): string
    {
        return $this->profile_image ? asset('storage/'.$this->profile_image) : '';
    }
}
