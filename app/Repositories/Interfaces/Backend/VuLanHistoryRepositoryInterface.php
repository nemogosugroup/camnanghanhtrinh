<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface VuLanHistoryRepositoryInterface extends RepositoryInterface
{
    public function getAll();
    public function addModel( $model );
    public function getById( $id );
}