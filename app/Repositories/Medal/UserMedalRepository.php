<?php

namespace App\Repositories\Medal;

use App\Models\UserMedal;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\UserMedalRepositoryInterface;

class UserMedalRepository extends BaseRepository implements UserMedalRepositoryInterface
{
    protected $model;

    public function __construct(UserMedal $model)
    {
        $this->model = $model;
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

    public function getUserMedalData(): array
    {
        $result = [
            'medal_categories' => [],
            'medals' => [],
        ];

        $catDuplicate = [];
        $this->model->query()
            ->with('medal', function ($query) {
                $query->with('category');
            })
            ->where('user_medals.user_id', auth()->user()->id)
            ->each(function ($item) use (&$result, &$catDuplicate) {
                $result['medals'][] = [
                    'id' => $item->medal->id,
                    'title' => $item->medal->title
                ];

                if (!in_array($item->medal->category->id, $catDuplicate)) {
                    $result['medal_categories'][] = [
                        'id' => $item->medal->category->id,
                        'title' => $item->medal->category->title
                    ];
                }

                $catDuplicate[] = $item->medal->category->id;
            });

        return $result;
    }
}