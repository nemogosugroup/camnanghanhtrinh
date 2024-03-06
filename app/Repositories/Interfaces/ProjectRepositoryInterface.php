<?php

namespace App\Repositories\Interfaces;

//use App\Repositories\RepositoryInterface;

interface ProjectRepositoryInterface
{
    public function getAllProject(array $params);

    public function getProjectById($id);

    public function deleteProject($id);

    public function createOrder(array $projectDetails);

    public function updateProject($id, array $newDetails);

    public function exportProject();
}

