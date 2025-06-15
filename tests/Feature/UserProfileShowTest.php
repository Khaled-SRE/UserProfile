<?php

namespace Tests\Feature;

use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserProfileShowTest extends TestCase
{
    use RefreshDatabase;

    const USER_PROFILE_SHOW_ROUTE = 'user-profile.show';

    public function test_show_user_profile_successfully(): void
    {
        $userProfile = UserProfile::factory()->create();

        $response = $this->getJson(route(self::USER_PROFILE_SHOW_ROUTE, ['userId' => $userProfile->user_id]));

        $response->assertOk();
        $response->assertJson([
            'status' => true,
            'code' => Response::HTTP_OK,
            'message' => __('Operation successful.'),
            'data' => [
                'user_id' => $userProfile->user_id,
                'email' => $userProfile->email,
                'first_name' => $userProfile->first_name,
                'last_name' => $userProfile->last_name,
                'date_of_birth' => $userProfile->date_of_birth,
                'locale' => $userProfile->locale,
                'profile_image' => $userProfile->profile_image,
            ],
        ]);
    }

    public function test_show_user_profile_not_found(): void
    {
        $response = $this->getJson(route(self::USER_PROFILE_SHOW_ROUTE, ['userId' => 999999]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'status' => false,
            'message' => __('Invalid user ID format.'),
        ]);
    }
}
