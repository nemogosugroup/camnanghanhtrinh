<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface UserMedalRepositoryInterface extends RepositoryInterface
{
    public function addModel( $model );

    public function getUserMedalData();
}