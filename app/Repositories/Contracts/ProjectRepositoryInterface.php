<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProjectRepositoryInterface
{
    public function all(Request $request);
    // public function find($project);
    // public function create(Request $request);
    // public function update($project, Request $request);
    // public function delete($project);
}