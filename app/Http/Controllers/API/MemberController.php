<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MemberResource::collection(Member::all());
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'team_id' => [
                'required',
                'integer',
                Rule::exists('teams', 'id')->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {

            try {
                $member = DB::transaction(function () use ($request) {
                    $member = Member::create($request->all());

                    return $member;
                });

                return response()->json(['success' => true, 'message' => "Member created successfully", 'data' => new MemberResource($member)], 201);
            } catch (\Throwable $th) {
                Log::error($th);
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
            return new MemberResource(Member::with('projects')->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Member not found'], 404);
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
            $member = Member::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'team_id' => [
                    'sometimes',
                    'required',
                    'integer',
                    Rule::exists('teams', 'id')->whereNull('deleted_at'),
                ]
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $validatedData = $validator->validated();
            $member = DB::transaction(function () use ($member, $validatedData) {
                $member->update($validatedData);

                return $member;
            });

            return response()->json(['success' => true, 'message' => "Team updated successfully", 'data' => new MemberResource($member)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Member not found', 'success' => false], 404);
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
            $member = Member::findOrFail($id);
            $message = 'Member ' . $member->name . ' deleted successfully';

            $member->delete();
            return response()->json(['message' => $message, 'success' => true], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Member not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    /**
     * Update Team of member
     */
    public function updateTeam(Request $request, string $id)
    {
        try {
            $member = Member::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'team_id' => [
                    'sometimes',
                    'required',
                    'integer',
                    Rule::exists('teams', 'id')->whereNull('deleted_at'),
                ]
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $validatedData = $validator->validated();
            $member = DB::transaction(function () use ($member, $validatedData) {
                $member->update($validatedData);

                return $member;
            });

            return response()->json(['success' => true, 'message' => "Member team updated successfully", 'data' => new MemberResource($member)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Member not found', 'success' => false], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }
}
