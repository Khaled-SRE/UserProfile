<?php

namespace Tests\Feature;

use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileDeleteTest extends TestCase
{
    use RefreshDatabase;

    const USER_PROFILE_DELETE_ROUTE = 'user-profile.destroy';

    const USER_PROFILE_STORE_OR_UPDATE_IMAGE_ROUTE = 'user-profile.image.store';

    public function test_delete_user_profile_successfully(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar1.jpg');
        $userProfile = UserProfile::factory()->create();
        $userProfileData = UserProfile::factory()->make([
            'user_id' => $userProfile->user_id,
            'profile_image' => $file,
        ])->toArray();
        $response = $this->postJson(route(self::USER_PROFILE_STORE_OR_UPDATE_IMAGE_ROUTE, ['userId' => $userProfile->user_id]), $userProfileData);
        $response->assertOk();
        Storage::disk('public')->assertExists($userProfile->fresh()->profile_image);
        $response = $this->deleteJson(route(self::USER_PROFILE_DELETE_ROUTE, ['userId' => $userProfile->user_id]));
        $response->assertOk();
        $response->assertJson([
            'status' => true,
            'code' => Response::HTTP_OK,
            'message' => __('Operation successful.'),
            'data' => 'User profile deleted successfully.',
        ]);
        Storage::disk('public')->assertMissing($userProfile->profile_image);
        $this->assertDatabaseMissing('user_profile', ['user_id' => $userProfile->user_id]);
    }
}
