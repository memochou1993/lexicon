<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectDispatchRequest;
use App\Models\Hook;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class DispatchController extends Controller
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
     * Dispatch events to client.
     *
     * @param  ProjectDispatchRequest  $request
     * @return JsonResponse
     */
    public function __invoke(ProjectDispatchRequest $request)
    {
        /** @var Project $project */
        $project = $request->user();

        $project->hooks->each(function (/** @var Hook $hook */ $hook) use ($request) {
            Http::retry(3, 500)
                ->withHeaders([
                    'Authorization' => sprintf('Bearer %s', $request->bearerToken())
                ])
                ->post($hook->url, [
                    'events' => $request->input('events'),
                ])
                ->throw();
        });

        return response()->json(null, Response::HTTP_ACCEPTED);
    }
}
