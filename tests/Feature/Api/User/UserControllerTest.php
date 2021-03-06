<?php

namespace Tests\Feature\Api\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testShow()
    {
        /** @var User $user */
        $user = Sanctum::actingAs($this->user);
        $user->roles()->save(Role::factory()->make());

        $this->json('GET', 'api/user', [
            'relations' => 'roles,roles.permissions,teams,projects',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'roles' => [
                        [
                            'permissions',
                        ],
                    ],
                    'teams',
                    'projects',
                ],
            ])
            ->assertJson([
                'data' => $user->toArray(),
            ]);
    }

    /**
     * @return void
     */
    public function testUpdate()
    {
        Sanctum::actingAs($this->user);

        $data = User::factory()->make([
            'name' => 'New User',
        ])->toArray();

        $this->json('PATCH', 'api/user', $data)
            ->assertOk();
    }

    /**
     * @return void
     */
    public function testUpdateDuplicate()
    {
        Sanctum::actingAs($this->user);

        $data = User::factory()->create()->toArray();

        $this->json('PATCH', 'api/user', $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }
}
