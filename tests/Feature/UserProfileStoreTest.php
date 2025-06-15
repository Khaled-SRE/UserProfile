<?php

namespace Tests\Feature;

use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileStoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const USER_PROFILE_STORE_ROUTE = 'user-profile.store';

    public function test_store_user_profile_room_successfully(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $userProfileData = UserProfile::factory()->make()->toArray();

        $userProfileData['profile_image'] = $file;

        $response = $this->postJson(route(self::USER_PROFILE_STORE_ROUTE, ['userId' => $userProfileData['user_id']]), $userProfileData);

        $response->assertCreated();
        $response->assertJsonStructure([]);
        $fullPath = UserProfile::first()->profile_image;
        $relativePath = str_replace(asset('storage').'/', '', $fullPath);
        $userProfileData['profile_image'] = $relativePath ?: null;
        $this->assertDatabaseHas('user_profile', $userProfileData);
        Storage::disk('public')->assertExists($fullPath);
    }

    public function test_store_user_profile_with_invalid_email(): void
    {
        $userProfileData = UserProfile::factory()->make()->toArray();
        $userProfileData['email'] = 'not-an-email';

        $response = $this->postJson(route(self::USER_PROFILE_STORE_ROUTE, ['userId' => $userProfileData['user_id']]), $userProfileData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_store_user_profile_with_missing_required_fields(): void
    {
        $userProfileData = [];
        $userId = 1;
        $response = $this->postJson(route(self::USER_PROFILE_STORE_ROUTE, ['userId' => $userId]), $userProfileData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'first_name', 'locale']);
    }
}
