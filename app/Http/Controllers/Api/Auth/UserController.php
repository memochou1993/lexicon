<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * Instantiate a new controller instance.
     *
     * @param  UserService  $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @param  UserStoreRequest  $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userService->store($request);

        return new UserResource($user);
    }
}
