<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_project_index_returns_a_list_of_projects(): void
    {
        $projects = Project::factory()->count(3)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description'
                    ],
                ],
            ]);


    }

    public function test_the_project_show_returns_a_single_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                ],
            ]);
    }

    public function test_creating_new_project(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Test Project',
                    'description' => 'Test Description',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);
    }

    public function test_updating_existing_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Test Project',
                    'description' => 'Test Description',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);
    }

    public function test_deleting_existing_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(200);

    }

    public function test_add_members_to_projects(): void
    {
        $project = Project::factory()->create();
        $team = Team::factory()->create();
        $members = Member::factory()->count(3)->create([
            'team_id' => $team->id,
        ]);

        $response = $this->putJson("/api/projects/{$project->id}/members/add", [
            'members' => $members->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'member_count' => 3,
                ],
            ]);

        $this->assertDatabaseHas('member_project', [
            'project_id' => $project->id,
            'member_id' => $members->first()->id,
        ]);
    }
}
