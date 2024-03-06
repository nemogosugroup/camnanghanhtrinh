<?php

namespace App\Repositories\Project;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
class ProjectRepository implements ProjectRepositoryInterface
{
    
    public function getAllProject(array $params){
        return Project::orderBy("id","desc")->get();
    }

    public function getProjectById($id){
        return Project::findOrFail($id);
    }

    public function deleteProject($id){
        Project::destroy($id);
    }

    public function createOrder(array $projectDetails){
        return Project::create($projectDetails);
    }

    public function updateProject($id, array $newDetails){
        return Project::whereId($id)->update($newDetails);
    }

    public function exportProject(){
        return Project::all();
    }
}
