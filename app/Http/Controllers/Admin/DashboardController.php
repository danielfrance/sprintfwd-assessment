<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiCall;
use App\Models\Member;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function getApiCalls()
    {
        $projectCalls = $this->getModelData('project');
        $teamCalls = $this->getModelData('team');
        $memberCalls = $this->getModelData('member');

        return response()->json([
            'project' => $projectCalls,
            'team' => $teamCalls,
            'member' => $memberCalls,
        ]);
    }

    public function getModelData($model)
    {
        $data = ApiCall::where('path', 'like', "%$model%")->select('method', DB::raw('count(*) as total'))
            ->groupBy('method')
            ->get();

        return $data;
    }

    public function getDashboardData()
    {
        $projects = Project::count();
        $teams = Team::count();
        $members = Member::count();

        return response()->json([
            'projects' => $projects,
            'teams' => $teams,
            'members' => $members,
        ]);
    }
}
