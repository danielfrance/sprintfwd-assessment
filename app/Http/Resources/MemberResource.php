<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "team_id" => $this->when(request()->is('api/members*'), $this->team_id),
            "team_name" => $this->when(request()->is('api/members*'), $this->team->name),
            "projects" => ProjectResource::collection($this->whenLoaded('projects')),
            "project_count" => $this->when(request()->is('api/members'), $this->projects->count()),
            
        ];
    }
}

