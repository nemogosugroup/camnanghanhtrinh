<?php

namespace App\Repositories\Vulan;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\VuLanHistoryRepositoryInterface;

class VulanHistoryRepository extends BaseRepository implements VuLanHistoryRepositoryInterface
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

    public function convertContent(int $templateId, string $rqContent, array $filesData)
    {
        $result = json_decode($rqContent, true);

        if ($templateId === 1) {
            $result['slider_1']['items'] = $filesData;
        } else if ($templateId === 2) {

        }
        return $result;
    }
}