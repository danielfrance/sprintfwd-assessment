<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'members' => [
                'array',
                Rule::exists('members', 'id')->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {

            try {
                $project = DB::transaction(function () use ($request) {
                    $project = Project::create($request->all());
                    $project->members()->sync($request->members);

                    return $project;
                });

                return response()->json(['success' => true, 'message' => "Project created successfully", 'data' => new ProjectResource($project)], 201);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new ProjectResource(Project::with('members')->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $project = Project::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    $validator->errors(),
                    422
                );
            }

            $project = DB::transaction(function () use ($request, $project) {
                $project->update($request->all());
                return $project;
            });

            return response()->json(['success' => true, 'message' => "Project updated successfully", 'data' => new ProjectResource($project)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $project = Project::findOrFail($id);

            $project->members()->detach();
            $project->delete();

            return response()->json(['success' => true, 'message' => "Project deleted successfully. Members were also removed from the project"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Add members of project
     */

    public function addMembers(Request $request, string $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'members' => 'required|array',
                'members.*' => [
                    'integer',
                    Rule::exists('members', 'id')->whereNull('deleted_at'),
                ],
            ]);

            if ($validator->fails()) {
                // // Manually check for invalid member IDs
                if (is_array($request->members) && count($request->members) > 0) {
                    $invalidIds = array_filter($request->members, function ($id) {
                        return !Member::whereNull('deleted_at')->find($id);
                    });

                    if ($invalidIds) {
                        return response()->json(['message' => 'Invalid member IDs: ' . implode(', ', $invalidIds)], 422);
                    }
                }

                return response()->json($validator->errors(), 422);
            }




            $project = DB::transaction(function () use ($request, $project) {
                $project->members()->sync($request->members);
                return $project;
            });

            return response()->json(['success' => true, 'message' => "Project updated successfully", 'data' => new ProjectResource($project)], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Remove members of project
     */

    public function removeMembers(Request $request, string $id)
    {
        // TODO: this code is repeated from addMembers. Refactor to a common method

        try {
            $project = Project::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'members' => 'required|array',
                'members.*' => [
                    'integer',
                    Rule::exists('members', 'id')->whereNull('deleted_at'),
                ],
            ]);

            if ($validator->fails()) {
                // // Manually check for invalid member IDs
                if (is_array($request->members) && count($request->members) > 0) {
                    $invalidIds = array_filter($request->members, function ($id) {
                        return !Member::whereNull('deleted_at')->find($id);
                    });

                    if ($invalidIds) {
                        return response()->json(['message' => 'Invalid member IDs: ' . implode(', ', $invalidIds)], 422);
                    }
                }

                return response()->json($validator->errors(), 422);
            }




            $project = DB::transaction(function () use ($request, $project) {
                $project->members()->detach($request->members);
                return $project;
            });

            return response()->json(['success' => true, 'message' => "Project updated successfully", 'data' => new ProjectResource($project)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Project not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }
}
