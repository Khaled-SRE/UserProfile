<?php

namespace App\Services;

use App\Http\Resources\UserProfileShowResource;
use App\Http\Resources\UserProfileStoreResource;
use App\Repositories\UserProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileService extends BaseService
{
    public function __construct(UserProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        // Upload image if provided
        if (isset($data['profile_image']) && $data['profile_image'] instanceof UploadedFile) {
            $upload = app(FileUploadService::class)->upload('uploads/profile_images', $data['profile_image']);
            $data['profile_image'] = $upload['path'];
        }

        $profile = $this->repository->create($data);

        return $this->successResponse(new UserProfileStoreResource($profile), api('Profile saved successfully.'), 201);
    }

    public function findBy($attribute, $value)
    {
        try {
            $profile = $this->repository->findBy($attribute, $value);
        } catch (\Exception $e) {
            return $this->errorResponse(api('Invalid user ID format.'), null, 422);
        }

        return $this->successResponse(new UserProfileShowResource($profile));
    }

    public function update(array $data, $id, $resource = true)
    {
        try {
            $profile = $this->repository->update($data, $this->repository->findBy('user_id', $id)->id);
        } catch (\Throwable $th) {
            return $this->errorResponse(api('unexpected general error'), null, 400);
        }

        return $this->successResponse(new UserProfileShowResource($profile), api('Profile updated successfully.'));
    }

    public function updateBy(array $data, $userId)
    {
        $profile = $this->repository->findBy('user_id', $userId);

        if (! $profile) {

            return $this->errorResponse(api('User profile not found.'), null, 404);
        }

        // Handle profile image update
        if (isset($data['profile_image']) && $data['profile_image'] instanceof UploadedFile) {
            $this->removeExistingProfileImage($userId);
            Log::info('Updating user profile');

            $data['profile_image'] = $this->uploadProfileImage($data['profile_image']);
        }

        // Update profile
        $profile = $this->repository->update($data, $profile->id);

        return $this->successResponse(new UserProfileShowResource($profile), api('Profile updated successfully.'));
    }

    public function deleteBy($attribute, $value)
    {
        try {
            $profile = $this->repository->findBy($attribute, $value);

            if (! $profile) {
                return $this->errorResponse(api('User profile not found.'), null, 404);
            }
            $this->removeExistingProfileImage($value);

            $profile->delete();

            return $this->successResponse(api('User profile deleted successfully.'));
        } catch (\Exception $e) {
            return $this->errorResponse(api('Failed to delete user profile.'), $e->getMessage(), 500);
        }
    }

    protected function uploadProfileImage(UploadedFile $file): string
    {
        $upload = app(FileUploadService::class)->upload('uploads/profile_images', $file);

        return $upload['path']; // Only return the path for DB storage
    }

    public function removeExistingProfileImage($userId)
    {
        $profile = $this->repository->findBy('user_id', $userId);
        if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
            Storage::disk('public')->delete($profile->profile_image);
            $profile->update(['profile_image' => null]);
        }

        return $this->successResponse(api('profile Image deleted successfully.'));

    }
}
