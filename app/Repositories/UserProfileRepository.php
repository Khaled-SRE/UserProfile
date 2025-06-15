<?php

namespace App\Repositories;

use App\Models\UserProfile;

class UserProfileRepository extends BaseRepository
{
    /**
     * UserProfileRepository constructor.
     *
     * Inject the UserProfile model into the base repository
     * so the base repository methods operate on the UserProfile model.
     */
    public function __construct(UserProfile $model)
    {
        parent::__construct($model);
    }
}
