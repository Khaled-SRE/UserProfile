<?php

namespace Database\Factories;

use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = UserProfile::class;

    public function definition()
    {
        static $userId = 1;

        return [
            'user_id' => $userId++,  // unique user_id incrementing from 1
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $this->faker->date('Y-m-d', '2000-01-01'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->phoneNumber,
            'locale' => $this->faker->randomElement(['en', 'ar']),
            'profile_image' => null, // or $this->faker->imageUrl() for example
        ];
    }
}
