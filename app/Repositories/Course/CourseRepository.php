<?php

namespace App\Repositories\Course;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\Backend\CourseRepositoryInterface;
class CourseRepository extends BaseRepository implements CourseRepositoryInterface
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
        ## Search by category ##
        if(isset($params['slug']) && $params['slug'] != null){
            $category_id = $this->modelCategory->where('slug', $params['slug'])->select('id')->first();
            if($category_id)
                $model->where('category_id', $category_id['id']);
        }
        ## Search by title ##
        if ($params['title'] != null) {
            $model->where('title', 'Like', '%' . $params['title'] . '%');
        }
        $data = $model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->latest()->paginate($params['limit']);
        return $data->toArray();
    }
    // get post by id
    public function getPostById($id){
        $data = $this->model->with(['category' => function ($query) {
            $query->select('id', 'title');
        }])->find($id);
        return $data->toArray();
    }
    // get all categories
    public function getCategories(){
        return $this->modelCategory->select('id','slug','title')->get()->toArray();
    }
}