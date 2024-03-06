<?php

namespace App\Repositories\Equipment;

use App\Models\Equipment;
use App\Models\UserEquipment;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\UserEquipmentRepositoryInterface;

class UserEquipmentRepository extends BaseRepository implements UserEquipmentRepositoryInterface
{
    protected $model;
    public function __construct(){}
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

    public function exists(array $params)
    {
        return $this->model->query()
            ->where('user_id', $params['user_id'])
            ->where('equipment_id', $params['equipment_id'])
            ->exists();
    }

    public function getInventoryItemsByUserId(array $params): array
    {
        return $this->model->query()
            ->with('equipment')
            ->where('user_id', $params['user_id'])
            ->where('position', $params['position'])
            ->get()->toArray();
    }

    public function getEquippedItemsByUserId(int $userId): array
    {
        $result = [];

        $items = $this->model->query()
            ->with('equipment')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();

        foreach ($items as $item) {
            $result[$item->position] = [
                'ue_id' => $item->id,
                'title' => $item->equipment->title,
                'description_in_game' => $item->equipment->description_in_game,
                'url_image' => $item->equipment->url_image
            ];
        }

        return $result;
    }

    public function removeEquippedItemById(int $ueId)
    {
        return $this->model->query()
            ->where('id', $ueId)
            ->update([
                'status' => 0
            ]);
    }

    public function useEquipmentById(int $ueId, int $position)
    {
        $oldItem = $this->model->query()
            ->where([
                'user_id' => auth()->user()->id,
                'position' => $position,
                'status' => 1
            ])->first();

        if (!is_null($oldItem)) {
            $oldItem->update(['status' => 0]);
        }

        return $this->model->query()
            ->where('id', $ueId)
            ->update([
                'status' => 1
            ]);
    }
}