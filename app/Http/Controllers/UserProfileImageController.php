<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserProfileImageRequest;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;

class UserProfileImageController extends Controller
{
    /**
     * The UserProfileService instance.
     *
     * @var \App\Services\UserProfileService
     */
    protected $userProfileService;

    /**
     * Create a new controller instance.
     */
    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    /**
     * Store or update profile Image.
     */
    public function store(StoreUserProfileImageRequest $request, int $userId): JsonResponse
    {
        $response = $this->userProfileService->updateBy($request->validated(), $userId);

        return response()->json($response, $response['code']);
    }

    /**
     * Delete profile Image.
     */
    public function destroy(int $userId): JsonResponse
    {
        $response = $this->userProfileService->removeExistingProfileImage($userId);

        return response()->json($response, $response['code']);
    }
}
