<?php

namespace App\Repositories\Equipment;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\TypeEquipmentRepositoryInterface;

class TypeEquipmentRepository extends BaseRepository implements TypeEquipmentRepositoryInterface
{
    protected $model;
    protected $modelCategory;

    public function __construct()
    {
    }

    //get model
    public function getModel()
    {
        return $this->model;
    }

    // set model
    public function addModel($modelData)
    {
        $this->model = $modelData;
    }

    // set model category
    public function setModelCategory($modelCategory)
    {
        $this->modelCategory = $modelCategory;
    }

    // get all type equipment
    public function list(array $params)
    {
        $model = $this->model->query();
        ## Search by title ##
        if ($params['title'] != null) {
            $model->where('title', 'Like', '%' . $params['title'] . '%');
        }
        ## Search by position ##
        if ($params['position'] != null) {
            $model->where('position', $params['position']);
        }
        $data = $model->with(['category' => function ($query) {
            $query->select('id', 'title', 'slug');
        }])->latest()->paginate($params['limit']);

        return $data->toArray();
    }

    public function getTypeById(int $id)
    {
        $data = $this->model->query()->find($id);

        return $data->toArray();
    }

    public function getCategories()
    {
        return $this->modelCategory->select('id', 'title', 'slug')->get()->toArray();
    }
}