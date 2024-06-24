<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProjectRepositoryInterface
{
    public function all(Request $request);
    public function myProjects(Request $request);
    public function find($project);
    public function findFile($file);
    public function create(Request $request);
    public function update(Request $request,$project);
    public function delete($project);
    public function deleteFile($file);
}