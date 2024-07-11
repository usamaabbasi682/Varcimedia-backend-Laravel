<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface 
{
    
    public function all(Request $request) 
    {
        $query = Project::query();

        $query->when($request->has('search'), function ($query) use($request) {
            return $query->whereAny(['title','name'], 'like', '%'.$request->get('search').'%');
        });

        $query->when($request->has('work_status'),function($query) use($request) {
            return $query->where('work_status',$request->get('work_status'));
        });

        $query->when(auth('sanctum')->user()->hasRole('client'),function($query) use($request) {
             if ($request->get('insight') == '1') {
                return $query->where('user_id',auth('sanctum')->user()->id);
             } else {
                return $query->whereHas('user_projects', function ($query) {
                    $query->where('user_id', auth('sanctum')->id());
                });
             }
        });

        $query->when(auth('sanctum')->user()->hasRole('writer'),function($query) use($request) {
             if ($request->get('insight') == '1') {
                return $query->where('user_id',auth('sanctum')->user()->id);
             } else {
                return $query->whereHas('user_projects', function ($query) {
                    $query->where('user_id', auth('sanctum')->id());
                });
             }
        });

        $query->when(auth('sanctum')->user()->hasRole('editor'),function($query) use($request) {
             if ($request->get('insight') == '1') {
                return $query->where('user_id',auth('sanctum')->user()->id);
             } else {
                return $query->whereHas('user_projects', function ($query) {
                    $query->where('user_id', auth('sanctum')->id());
                });
             }
        });

        $projects = $query->orderBy('id','DESC')->paginate(8);
        return $projects;
    }
    
    public function myProjects(Request $request) 
    {
        $user = auth('sanctum')->user();
        $query = Project::query();

        $query->when($request->has('search'), function ($query) use($request) {
            return $query->whereAny(['title','name'], 'like', '%'.$request->get('search').'%');
        });

        $projects = $query->whereBelongsTo($user)->orderBy('id','DESC')->paginate(8);
        return $projects;
    }

    public function create(Request $request) 
    {
        try {
            $project = DB::transaction(function () use($request) {
                $user = auth('sanctum')->user();

                if ($user->hasRole('admin')) {
                    if($request->input('end_date') != '') 
                        $endDate = Carbon::parse($request->input('end_date'));
                    else 
                        $endDate = NULL;
                    
                } else {
                    $endDate = NULL;
                }

                $project = $user->projects()->create([
                    'title' => $request->input('title') ?? '',
                    'name' => $request->input('name') ?? '',
                    'description' => $request->input('description') ?? '',
                    'end_date' => $endDate,
                ]);

                if ($request->hasFile('file')) {
                    foreach ($request->file('file') as $file) {
                        $uploadedFile = FileUploadService::upload($file,'/public/projects');
                        $fileOriginalName = $uploadedFile->getClientOriginalName();
                        $fileUrl = $uploadedFile->uploaded_path . '/' . $uploadedFile->uploaded_name;
                        
                        $project->files()->create([
                            'original_name'=>$fileOriginalName,
                            'url' => $fileUrl,
                        ]);
                    }
                }

                if (!empty($request->input('associate_users'))) {
                    $project->user_projects()->attach($request->input('associate_users'));
                }

                return $project;
            });
        return $project;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function find($project)
    {
        return Project::find($project);
    }

    public function findFile($file)
    {
        return File::find($file);
    }


    public function update(Request $request,$project) 
    {
        try {
            $project = DB::transaction(function () use($project,$request)  {

                $user = auth('sanctum')->user();
                if ($user->hasRole('admin')) {
                    if($request->input('end_date') != '') 
                        $endDate = Carbon::parse($request->input('end_date'));
                    else 
                        $endDate = NULL;
                } else {
                    $endDate = NULL;
                }

                $userProject = Project::findOrFail($project);
                $project = $userProject->update([
                    'title' => $request->input('title') ?? '',
                    'name' => $request->input('name') ?? '',
                    'description' => $request->input('description') ?? '',
                    'end_date' => $endDate,
                    'work_status' => $request->input('work_status') ?? '',
                    'status' => $request->input('status') ?? '',
                ]);

                if ($request->hasFile('file')) {
                    foreach ($request->file('file') as $file) {
                        $uploadedFile = FileUploadService::upload($file,'/public/projects');
                        $fileOriginalName = $uploadedFile->getClientOriginalName();
                        $fileUrl = $uploadedFile->uploaded_path . '/' . $uploadedFile->uploaded_name;
                        
                        $userProject->files()->create([
                            'original_name'=>$fileOriginalName,
                            'url' => $fileUrl,
                        ]);
                    }
                }

                if (!empty($request->input('associate_users'))) {
                    $userProject->user_projects()->sync($request->input('associate_users'));
                }
                return $userProject;
            });
            return $project;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function delete($project) 
    {
        $project = Project::findOrFail($project);
        $project->delete();
    }
    
    public function deleteFile($file) 
    {
        $file = File::findOrFail($file);
        // Removing file from storage
        FileUploadService::delete('public'.$file->url);
        $file->delete();
    }
}