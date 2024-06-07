<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface 
{
    
    public function all(Request $request) 
    {
        $query = Project::query();

        $query->when($request->has('search'), function ($query) use($request) {
            return $query->whereAny(['full_name','username','email'], 'like', '%'.$request->get('search').'%');
        });

        $projects = $query->orderBy('id','DESC')->paginate(8);
        return $projects;
    }

}