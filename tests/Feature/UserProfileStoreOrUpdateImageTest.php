<?php

namespace Tests\Feature;

use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileStoreOrUpdateImageTest extends TestCase
{
    use RefreshDatabase;

    const USER_PROFILE_STORE_OR_UPDATE_IMAGE_ROUTE = 'user-profile.image.store';

    public function test_store_user_profile_image_successfully(): void
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar1.jpg')->size(1000); // 1MB
        $userProfile = UserProfile::factory()->create();
        $userProfileData = UserProfile::factory()->make([
            'user_id' => $userProfile->user_id,
            'profile_image' => $file,
        ])->toArray();
        $response = $this->postJson(route(self::USER_PROFILE_STORE_OR_UPDATE_IMAGE_ROUTE, ['userId' => $userProfile->user_id]), $userProfileData);

        $response->assertOk();
        Storage::disk('public')->assertExists($userProfile->fresh()->profile_image);
        $response->assertJson([
            'status' => true,
            'code' => Response::HTTP_OK,
            'message' => __('Profile updated successfully.'),
            'data' => [
                'user_id' => $userProfile->user_id,
                'email' => $userProfile->email,
                'first_name' => $userProfile->first_name,
                'last_name' => $userProfile->last_name,
                'date_of_birth' => $userProfile->date_of_birth,
                'locale' => $userProfile->locale,
                'profile_image' => asset('storage/'.$userProfile->fresh()->profile_image),
            ],
        ]);
    }

    public function test_store_user_profile_image_failure_with_invalid_size(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg')->size(3000); // 3MB
        $userProfile = UserProfile::factory()->create();
        $userProfileData = UserProfile::factory()->make([
            'user_id' => $userProfile->user_id,
            'profile_image' => $file,
        ])->toArray();
        $response = $this->postJson(route(self::USER_PROFILE_STORE_OR_UPDATE_IMAGE_ROUTE, ['userId' => $userProfile->user_id]), $userProfileData);

        $response->assertInvalid('profile_image');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        Storage::disk('public')->assertMissing($userProfile->fresh()->profile_image);
    }
}
