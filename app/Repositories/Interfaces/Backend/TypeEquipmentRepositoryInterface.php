<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface TypeEquipmentRepositoryInterface extends RepositoryInterface
{
    public function addModel( $model );
    public function list( array $params );
    public function getCategories();
    public function getTypeById(int $id);
}