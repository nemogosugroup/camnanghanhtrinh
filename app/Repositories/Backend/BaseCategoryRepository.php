<?php

namespace App\Repositories\Backend;

use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\CategoryMission;
class BaseCategoryRepository extends BaseRepository implements BaseCategoryRepositoryInterface
{
    protected $model;
    protected $modelCategory;
    public function __construct(){}
    //lấy model tương ứng
    public function getModel()
    {
        return $this->model;
    }

    public function addModel($modelData)
    {
        $this->model = $modelData;
    }
    //lấy model danh mục cha tương ứng
    public function setModelCategory($modelCategory)
    {
        $this->modelCategory = $modelCategory;
    }

    public function getAllCategories(array $params)
    {
        $medals = $this->model->select('id', 'title');
        ## Search by title ##
        if ($params['title'] != null) {
            $medals->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $medals->latest()->paginate($params['limit']);
        return $data->toArray();
    }

    public function getCategoriesParents( ){
        return $this->modelCategory->select('id', 'title')->get()->toArray();
    }
}