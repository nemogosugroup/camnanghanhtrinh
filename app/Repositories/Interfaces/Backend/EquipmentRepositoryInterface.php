<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface EquipmentRepositoryInterface extends RepositoryInterface
{

    public function getAllPost( array $param );
    public function addModel( $model );
    public function getPostById( int $id );
    public function getCategories();
    public function getAll();
    public function setModelCategory($modelCategory);
    public function getAllEquipment(array $params);
    public function getAllCategories( array $param );
}