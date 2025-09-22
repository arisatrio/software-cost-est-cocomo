<?php

namespace App\Http\Controllers;

use App\Enums\CostDriver;
use App\Enums\ScaleFactor;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
       $projects = Project::withActualData()->get();
        
        $avgEffortAccuracy = $projects->avg('effort_accuracy') ?? 0;
        $avgScheduleAccuracy = $projects->avg('schedule_accuracy') ?? 0;
        $avgPersonnelAccuracy = $projects->avg('personnel_accuracy') ?? 0;
        $avgOverallAccuracy = $projects->avg('overall_accuracy') ?? 0;
        $completedProjects = $projects->count();
        
        // Get all projects for the table (including those without actual data)
        $allProjects = Project::orderBy('created_at', 'desc')->get();
        
        return view('dashboard', compact(
            'allProjects', 'avgEffortAccuracy', 'avgScheduleAccuracy', 
            'avgPersonnelAccuracy', 'avgOverallAccuracy', 'completedProjects'
        ));
    }
}
