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

        $projects = $query->orderBy('id','DESC')->paginate(8);
        return $projects;
    }

    public function create(Request $request) 
    {
        try {
            $project = DB::transaction(function () use($request) {
                $user = Auth::user();
                
                $project = $user->projects()->create([
                    'title' => $request->input('title') ?? '',
                    'name' => $request->input('name') ?? '',
                    'description' => $request->input('description') ?? '',
                    'end_date' =>  Carbon::parse($request->input('end_date')) ?? '',
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
                    $project->user_project()->attach($request->input('associate_users'));
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
                $userProject = Project::findOrFail($project);
                $project = $userProject->update([
                    'title' => $request->input('title') ?? '',
                    'name' => $request->input('name') ?? '',
                    'description' => $request->input('description') ?? '',
                    'end_date' =>  Carbon::parse($request->input('end_date')) ?? '',
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
                    $userProject->user_project()->sync($request->input('associate_users'));
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