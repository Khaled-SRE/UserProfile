<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
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
     * Store a newly created user profile.
     */
    public function store(StoreUserProfileRequest $request): JsonResponse
    {
        $response = $this->userProfileService->create($request->validated());

        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified user profile.
     */
    public function show(int $userId): JsonResponse
    {
        $response = $this->userProfileService->findBy('user_id', $userId);

        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified user profile.
     */
    public function update(UpdateUserProfileRequest $request, int $userId): JsonResponse
    {
        $response = $this->userProfileService->update($request->validated(), $userId);

        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified user profile.
     */
    public function destroy(int $userId): JsonResponse
    {
        $response = $this->userProfileService->deleteBy('user_id', $userId);

        return response()->json($response, $response['code']);
    }
}
