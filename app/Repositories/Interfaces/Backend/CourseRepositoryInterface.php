<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function getAllPost( array $param );
    public function addModel( $model );
    public function getPostById( $id );
    public function getCategories();
    public function setModelCategory($modelCategory);
}