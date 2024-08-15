<?php

namespace App\Repositories\Vulan;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\VuLanRepositoryInterface;

class VulanRepository extends BaseRepository implements VuLanRepositoryInterface
{
    protected $model;

    public function __construct()
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function addModel($modelData)
    {
        $this->model = $modelData;
    }

    public function getAll()
    {
        $model = $this->model->query()->get();

        return $model->toArray();
    }

    public function getById($id)
    {
        $data = $this->model->find($id);

        return $data->toArray();
    }
}