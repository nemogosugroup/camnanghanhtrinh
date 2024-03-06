<?php

namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface MedalRepositoryInterface extends RepositoryInterface
{
    
    public function getAllMedal( array $param );
    public function getCategories();
    public function getPostById( $id );
}