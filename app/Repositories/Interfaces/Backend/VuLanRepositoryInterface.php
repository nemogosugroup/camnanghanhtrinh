<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface VuLanRepositoryInterface extends RepositoryInterface
{
    public function getAllHistory( $params );
    public function addModel( $model );
    public function getById( $id );
}