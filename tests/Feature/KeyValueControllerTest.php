<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\Key;
use App\Models\Language;
use App\Models\Project;
use App\Models\Team;
use App\Models\Value;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class KeyValueControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testStore()
    {
        $user = Sanctum::actingAs($this->user, ['update-key']);

        $team = $user->teams()->save(factory(Team::class)->make());
        $language = $team->languages()->save(factory(Language::class)->make());
        $form = $team->forms()->save(factory(Form::class)->make());
        $project = $team->projects()->save(factory(Project::class)->make());
        $key = $project->keys()->save(factory(Key::class)->make());

        $value = factory(Value::class)->make([
            'language_id' => $language->id,
            'form_id' => $form->id,
        ]);

        $this->json('POST', 'api/keys/'.$key->id.'/values', $value->toArray())
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => $value->makeHidden('language_id', 'form_id')->toArray(),
            ]);

        $this->assertDatabaseHas('values', $value->toArray());

        $this->assertCount(1, $key->values);
    }
}
