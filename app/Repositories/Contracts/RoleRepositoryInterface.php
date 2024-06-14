<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface RoleRepositoryInterface
{
    public function admin();
    public function client();
    public function writer();
    public function editor();
}