<?php

namespace App\Repositories\Medal;

use App\Repositories\Interfaces\Backend\CategoriesRepositoryInterface;
use App\Models\Medal;
use App\Models\CategoryMedal;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoriesRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return CategoryMedal::class;
    }

    public function getAllCategories(array $params)
    {
        $medals = CategoryMedal::select('id', 'title');
        ## Search by title ##
        if ($params['title'] != null) {
            $medals->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $medals->latest()->paginate($params['limit']);
        return $data->toArray();
    }
}