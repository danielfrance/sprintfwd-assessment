<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_members(): void
    {
        $team = Team::factory()->create();
        Member::factory()->count(3)->create([
            'team_id' => $team->id,
        ]);

        $response = $this->getJson('/api/members');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'city',
                        'state',
                        'country',
                        'team_id',
                    ],
                ],
            ]);

    }

    public function test_the_member_show_returns_a_single_member(): void
    {
        $team = Team::factory()->create();
        $member = Member::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->getJson("/api/members/{$member->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $member->id,
                    'first_name' => $member->first_name,
                    'last_name' => $member->last_name,
                    'city' => $member->city,
                    'state' => $member->state,
                    'country' => $member->country,
                    'team_id' => $member->team_id,
                ],
            ]);
    }

    public function test_creating_new_member():void
    {
        $team = Team::factory()->create();
        $response = $this->postJson('/api/members', [
            'first_name' => 'Test First Name',
            'last_name' => 'Test Last Name',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Test Country',
            'team_id' => $team->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'first_name' => 'Test First Name',
                    'last_name' => 'Test Last Name',
                    'city' => 'Test City',
                    'state' => 'Test State',
                    'country' => 'Test Country',
                    'team_id' => $team->id,
                ],
            ]);
    }

    public function test_creating_new_member_with_failure(): void
    {
        $response = $this->postJson('/api/members', [
            'first_name' => '',
            'last_name' => 'Test Last Name',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Test Country',
            'team_id' => 'Test Team ID',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'first_name' => [
                    'The first name field is required.',
                ],
            ]);
    }

    public function test_updating_existing_member(): void
    {
        $team = Team::factory()->create();
        $member = Member::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->putJson("/api/members/{$member->id}", [
            'first_name' => 'Test Updating First Name',
            'last_name' => 'Test Last Name',
            
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Test Updating First Name',
                    'team_id' => $team->id,
                ],
            ]);
    }

    public function test_deleting_existing_member(): void
    {
        $team = Team::factory()->create();
        $member = Member::factory()->create([
            'team_id' => $team->id,
        ]);

        $response = $this->deleteJson("/api/members/{$member->id}");

        $response->assertStatus(204);

    }

    public function test_updating_existing_member_team():void
    {
        $team = Team::factory()->create();
        $member = Member::factory()->create([
            'team_id' => $team->id,
        ]);

        $newTeam = Team::factory()->create();

        $response = $this->putJson("/api/members/{$member->id}", [
            'team_id' => $newTeam->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'team_id' => $newTeam->id,
                ],
            ]);
    }
}
