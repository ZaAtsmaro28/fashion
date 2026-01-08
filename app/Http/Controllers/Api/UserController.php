<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers(
            $request->query('search'),
            $request->query('per_page', 10)
        );

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());

        return new UserResource($user);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateProfile($request->user()->id, $request->validated());
        return new UserResource($user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->userService->changePassword($request->user()->id, $request->new_password);
        return response()->json(['message' => 'Password berhasil diperbarui']);
    }

    public function destroy(string $id, Request $request)
    {
        $this->userService->deleteUser($id, $request);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
