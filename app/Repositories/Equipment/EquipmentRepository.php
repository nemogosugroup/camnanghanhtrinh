<?php

namespace App\Repositories\Equipment;

use App\Models\CategoryEquipment;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;

class EquipmentRepository extends BaseRepository implements EquipmentRepositoryInterface
{
    protected $model;
    protected $modelCategory;
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
    // set model category
    public function setModelCategory($modelCategory)
    {
        $this->modelCategory = $modelCategory;
    }    
    // get all post
    public function getAllPost(array $params)
    {
        $model = $this->model->query();
        ## Search by title ##
        if ($params['title'] != null) {
            $model->where('title', 'Like', '%' . $params['title'] . '%');
        }
        ## Search by category ##
        if(isset($params['slug']) && $params['slug'] != null){
            $category_id = $this->modelCategory->where('slug', $params['slug'])->select('id')->first();
            if($category_id)
                $model->where('type_equipment_id', $category_id['id']);
        }
        $data = $model->with(['type' => function ($query) {
            $query->select('id', 'title', 'position', 'slug');
        }])->latest()->paginate($params['limit']);
        return $data->toArray();
    }
    // get post by id
    public function getPostById(int $id){
        $data = $this->model->with(['type' => function ($query) {
            $query->select('id', 'title', 'position');
        }])->find($id);
        return $data->toArray();
    }
    // get post by id
    public function getEquipmentById(int $id) {
        return $this->model->query()
            ->with(['type' => function ($query) {
                $query->select('id', 'title', 'position');
            }])->with(['medal' => function ($query) {
                $query->select('id', 'title');
            }])->find($id);
    }
    // get all categories
    public function getCategories(){
        return $this->modelCategory->whereNull('category_id')->select('id', 'title', 'slug')->get()->toArray();
    }
    // get all types
    public function getTypes(){
        return $this->model->select('id', 'title', 'position')->latest()->get()->toArray();
    }
    // get all medals
    public function getMedals(){
        return $this->model->select('id', 'title')->get()->toArray();
    }
    // get all equipment
    public function getAllEquipment(array $params)
    {
        $model = $this->model->query()
            ->select('equipment.*')
            ->join('type_equipment', 'type_equipment.id', '=', 'equipment.type_equipment_id');
        ## Search by title ##
        if ($params['title'] != null) {
            $model->where('equipment.title', 'Like', '%' . $params['title'] . '%');
        }
        ## Search by position ##
        if (isset($params['position']) && $params['position'] != null) {
            $model->where('type_equipment.position', $params['position']);
        }
        $model = $model->with(['type' => function ($query) {
            $query->select('id', 'title', 'position');
        }])->with(['medal' => function ($query) {
            $query->select('id', 'title');
        }]);
        $data = $model->orderBy('equipment.created_at', 'desc')->paginate($params['limit']);

        return $data->toArray();
    }

    public function getAllCategories(array $params)
    {
        $medals = CategoryEquipment::select('id', 'title');
        ## Search by title ##
        if ($params['title'] != null) {
            $medals->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $medals->latest()->paginate($params['limit']);
        return $data->toArray();
    }
}