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

    public function convertContent(string $rqContent, array $filesData)
    {
        $result = json_decode($rqContent, true);

        $result['slider_1']['items'] = $filesData;

        return $result;
    }

    public function convertContentSlider2(string $rqContent, array $filesData, array $mainFilesData)
    {
        $result = json_decode($rqContent, true);
        if (count($filesData) > 0) {
            $result['slider_2']['items'] = $filesData;
        }
        foreach ($result['slider_2']['main_items'] as $idx => $item) {
            $result['slider_2']['main_items'][$idx]['url'] = $mainFilesData[$idx]['url'];
        }

        return $result;
    }
}