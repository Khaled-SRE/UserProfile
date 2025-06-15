<?php

namespace Tests\Feature;

use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const USER_PROFILE_UPDATE_ROUTE = 'user-profile.update';

    public function test_update_user_profile_room_successfully(): void
    {
        $userOldProfile = UserProfile::factory()->create();

        $data = UserProfile::factory()->make([
            'user_id' => $userOldProfile->user_id,
        ])->toArray();

        $response = $this->putJson(route(self::USER_PROFILE_UPDATE_ROUTE, ['userId' => $userOldProfile->user_id]), $data);

        $response->assertOk();
        $response->assertJson([
            'status' => true,
            'code' => Response::HTTP_OK,
            'message' => __('Profile updated successfully.'),
            'data' => [
                'user_id' => $data['user_id'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'],
                'locale' => $data['locale'],
                'profile_image' => $data['profile_image'],
            ],
        ]);

        $this->assertDatabaseHas('user_profile', $data);
        $this->assertDatabaseMissing('user_profile', $userOldProfile->toArray());
    }

    // public function test_update_user_profile_with_invalid_email(): void
    // {
    //     $userOldProfile = UserProfile::factory()->create();

    //     $data = UserProfile::factory()->make([
    //         'user_id' => $userOldProfile->user_id,
    //     ])->toArray();
    //     $data['email'] = 'not-an-email';

    //     $response = $this->putJson(route(self::USER_PROFILE_UPDATE_ROUTE, ['userId' => $data['user_id']]), $data);

    //     $response->assertStatus(422);
    //     $response->assertJsonValidationErrors(['email']);
    //     unset($userOldProfile['created_at'], $userOldProfile['updated_at']);
    //     $this->assertDatabaseHas('user_profile', $userOldProfile->toArray());
    //     $this->assertDatabaseMissing('user_profile', $data);
    // }

    // public function test_update_user_profile_with_missing_required_fields(): void
    // {
    //     $data = [];
    //     $userId = 1;
    //     $response = $this->putJson(route(self::USER_PROFILE_UPDATE_ROUTE, ['userId' => $userId]), $data);

    //     $response->assertStatus(422);
    //     $response->assertJsonValidationErrors(['email', 'first_name', 'locale']);
    // }
}
