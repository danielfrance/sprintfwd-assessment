<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Resources\MemberResource;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeamResource::collection(Team::all());
    }

    //  
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $team = Team::create($request->all());
                return response()->json(['success' => true, 'message' => "Team created successfully", 'data' => new TeamResource($team)], 201);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        try {
            return new TeamResource(Team::with('members')->findOrFail($id));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
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
            $team = Team::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:500' . $team->id,
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $team = DB::transaction(function () use ($request, $team) {
                $team->update($request->all());
                return $team;
            });

            return response()->json(['success' => true, 'message' => "Team updated successfully", 'data' => new TeamResource($team)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Team not found', 'success' => false], 404);
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
            $team = new TeamResource(Team::findOrFail($id));
            $message = 'Team ' . $team->name . ' deleted successfully';

            //if team has members, tell user that members must be deleted first
            if ($team->members->count() > 0) {

                $membersResource = MemberResource::collection($team->members);


                return response()->json(['message' => 'This Team has active members. Delete or reassign the members first', 'success' => false, 'data' => $membersResource], 422);
            } else {
                $team->delete();
                return response()->json(['message' => $message, 'success' => true], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Team not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Get all members of a team
     */

    public function members(string $id)
    {
        try {
            $team = new TeamResource(Team::findOrFail($id));

            $members = $team->members;
            return response()->json(['success' => true, 'data' => $members], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Team not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }
}
