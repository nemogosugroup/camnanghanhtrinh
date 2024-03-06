<?php

namespace App\Repositories\Medal;

use App\Repositories\Interfaces\Backend\MedalRepositoryInterface;
use App\Models\Medal;
use App\Models\CategoryMedal;
use App\Repositories\BaseRepository;

class MedalRepository extends BaseRepository implements MedalRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Medal::class;
    }

    public function getAllMedal(array $params)
    {
        $medals = Medal::select('id', 'title', 'category_id');
        ## Search by title ##
        if ($params['title'] != null) {
            $medals->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $medals->with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->latest()->paginate($params['limit']);
        return $data->toArray();
    }
    public function getCategories()
    {
        return CategoryMedal::select('id', 'title')->get()->toArray();
    }

    public function getPostById($id)
    {
        $data = Medal::with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->select('id', 'title', 'category_id')->find($id);
        return $data->toArray();
    }
}