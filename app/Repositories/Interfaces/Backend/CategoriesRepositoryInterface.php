<?php
namespace App\Repositories\Interfaces\Backend;

use App\Repositories\RepositoryInterface;

interface CategoriesRepositoryInterface extends RepositoryInterface
{
    
    public function getAllCategories( array $param );
}