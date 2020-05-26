<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProjectIndexRequest;
use App\Http\Resources\ProjectResource as Resource;
use App\Services\ProjectService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    private ProjectService $projectService;

    /**
     * Instantiate a new controller instance.
     *
     * @param  ProjectService  $projectService
     */
    public function __construct(
        ProjectService $projectService
    ) {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  UserProjectIndexRequest  $request
     * @return AnonymousResourceCollection
     */
    public function index(UserProjectIndexRequest $request)
    {
        $teams = $this->projectService->getByUser(Auth::guard()->user(), $request);

        return Resource::collection($teams);
    }
}
