<?php
namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface CategoriesMissionRepositoryInterface extends RepositoryInterface
{
    
    public function getAllCategories( array $param );
}