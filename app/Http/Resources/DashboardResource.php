<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $projectsCount = Project::count();
        $completedProjectsCount = Project::where('work_status', 'completed')->count();
        $pendingProjectsCount = Project::where('work_status', 'pending')->count();
        $usersCount = $this->count();
        $users=User::has('projects')->orderBy('id','DESC')->simplePaginate(5);

        $clientTotalProjects = Project::whereBelongsTo(auth('sanctum')->user())->count();
        $clientTotalCompletedProjects = Project::whereBelongsTo(auth('sanctum')->user())
            ->where('work_status', 'completed')
            ->count();

        $clientTotalPendingProjects = Project::whereBelongsTo(auth('sanctum')->user())
            ->where('work_status', 'pending')
            ->count();
            
        $clientTotalAddedInProjects = Project::whereHas('user_projects', function ($query) {
                $query->where('user_id', auth('sanctum')->id());
        })->count();
        $projects = Project::whereHas('user_projects', function ($query) {
                $query->where('user_id', auth('sanctum')->id());
            })->simplePaginate(5);
            

        return $this->resource ?  
        [
            'total_projects' => number_format($projectsCount, 0, '.', ','),
            'total_users' => number_format($usersCount, 0, '.', ','),
            'total_pending_projects' => number_format($pendingProjectsCount, 0, '.', ','),
            'total_completed_projects' => number_format($completedProjectsCount, 0, '.', ','),
            'client_total_projects' => number_format($clientTotalProjects, 0, '.', ','),
            'client_total_completed_projects' => number_format($clientTotalCompletedProjects, 0, '.', ','),
            'client_total_pending_projects' => number_format($clientTotalPendingProjects, 0, '.', ','),
            'client_total_added_in_projects' => number_format($clientTotalAddedInProjects, 0, '.', ','),
            'users' => UserResource::collection($users),
            'projects' => ProjectResource::collection($projects),
            'pagination' => [
                'user' => [
                    'current_page' => $users->currentPage(),
                    'next_page_url' => $users->nextPageUrl(),
                    'prev_page_url' => $users->previousPageUrl(),
                ],
                'project' => [
                    'current_page' => $projects->currentPage(),
                    'next_page_url' => $projects->nextPageUrl(),
                    'prev_page_url' => $projects->previousPageUrl(),
                ]
            ]
        ] : [];
    }
}
