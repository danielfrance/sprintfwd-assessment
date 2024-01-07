<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_teams(): void
    {
        $teams = Team::factory()->count(3)->create();

        $response = $this->getJson('/api/teams');

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

    public function test_the_team_show_returns_a_single_team(): void
    {
        $team = Team::factory()->create();

        $response = $this->getJson("/api/teams/{$team->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                ],
            ]);
    }

    public function test_creating_new_team(): void
    {
        $response = $this->postJson('/api/teams', [
            'name' => 'Test Team',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Team created successfully',
                'data' => [
                    'name' => 'Test Team',
                    'description' => 'Test Description',
                ],
            ]);

        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
            'description' => 'Test Description',
        ]);
    }

    public function test_creating_new_team_with_failure(): void
    {
        $response = $this->postJson('/api/teams', [
            'name' => '',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'name' => [
                    'The name field is required.',
                ],
            ]);
    }

    public function test_updating_existing_team(): void
    {
        $team = Team::factory()->create();

        $response = $this->putJson("/api/teams/{$team->id}", [
            'name' => 'Test Team',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Team updated successfully',
                'data' => [
                    'name' => 'Test Team',
                    'description' => 'Test Description',
                ],
            ]);

        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
            'description' => 'Test Description',
        ]);
    }

    public function test_delete_existing_team(): void
    {
        $team = Team::factory()->create();

        $response = $this->deleteJson("/api/teams/{$team->id}");

        $response->assertStatus(200);

    }

    public function test_get_all_team_members(): void
    {
        $team = Team::factory()->create();
       $members = Member::factory()->count(3)->create([
            'team_id' => $team->id,
        ]);

        $response = $this->getJson("/api/teams/{$team->id}/members");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'team_id',
                    ],
                ],
            ]);
        }
}
