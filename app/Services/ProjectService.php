<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ProjectService
{
    /**
     * @var Team
     */
    private Team $team;

    /**
     * @var Project
     */
    private Project $project;

    /**
     * Instantiate a new service instance.
     *
     * @param  Team  $team
     * @param  Project  $project
     */
    public function __construct(
        Team $team,
        Project $project
    ) {
        $this->team = $team;
        $this->project = $project;
    }

    /**
     * @param  int  $team_id
     * @param  array  $relations
     * @param  int  $per_page
     * @return LengthAwarePaginator
     */
    public function getByTeam(int $team_id, array $relations, int $per_page): LengthAwarePaginator
    {
        return $this->team->find($team_id)->projects()->with($relations)->paginate($per_page);
    }

    /**
     * @param  Project  $project
     * @param  array  $relations
     * @return Model
     */
    public function get(Project $project, array $relations): Model
    {
        return $this->project->with($relations)->find($project->id);
    }

    /**
     * @param  int  $teamId
     * @param  array  $data
     * @return Model
     */
    public function storeByTeam(int $teamId, array $data): Model
    {
        return $this->team->find($teamId)->projects()->create($data);
    }

    /**
     * @param  Project  $project
     * @param  array  $data
     * @return Model
     */
    public function update(Project $project, array $data): Model
    {
        $project = $this->project->find($project->id);

        $project->update($data);

        return $project;
    }

    /**
     * @param  Project  $project
     * @return bool
     */
    public function destroy(Project $project): bool
    {
        return $this->project->destroy($project->id);
    }

    /**
     * @param  Project  $project
     * @param  array  $language_ids
     */
    public function attachLanguage(Project $project, array $language_ids): void
    {
        $project->languages()->syncWithoutDetaching($language_ids);
    }

    /**
     * @param  Project  $project
     * @param  int  $language_id
     */
    public function detachLanguage(Project $project, int $language_id): void
    {
        $project->languages()->detach($language_id);
    }
}
