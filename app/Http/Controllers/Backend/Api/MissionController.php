<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Backend\Interfaces\BasePostRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Mission;
use App\Models\Level;
use App\Models\Equipment;
use App\Models\SubCategoryMission;
class MissionController extends Controller
{
    /**
     * @var BasePostRepositoryInterface
     */
    protected $postRepo;
    protected $msg;
    protected $helper;
    protected $model;
    public function __construct(
        BasePostRepositoryInterface $postRepo, 
        Message $message, 
        Helpers $helper,
        Mission $model, 
        SubCategoryMission $modelCategory
    )
    {
        $this->postRepo = $postRepo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->model = $model;
        $this->postRepo->addModel($model);
        $this->postRepo->setModelCategory($modelCategory);
    }

    public function index(Request $request)
    {
        try {
            $params          = $request->all();
            $lists           = $this->postRepo->getAllMission($params);
            $missions        = $this->postRepo->getAll();
            $levels          = Level::all(['id', 'level'])->toArray();
            $equipments      = Equipment::all(['id', 'title'])->toArray();
            $listCategories  = $this->postRepo->getCategories();
            $results = array(
                'success' => true,
                'data' => $lists,
                'categories' => $listCategories,
                'levels' => $levels,
                'missions' => $missions,
                'equipments' => $equipments,
                'message' => $this->msg->getSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        }           
    }

    public function show($id)
    {
        // $product = $this->postRepo->find($id);

        // return view('home.product', ['product' => $product]);
    }

    public function create(Request $request)
    {
        try {
            $param = $request->all();
            $param['slug'] = $this->helper->getSlug($param['title'], $this->model);
            $data = $this->postRepo->create($param);
            $result = $this->postRepo->getMissionById($data->id);
            $results = array(
                'success' => true,
                'data' => $result,
                'message' => $this->msg->createSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                //'message' => $this->msg->createError(),
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        } 
    }

    public function update(Request $request, $id)
    {       
        try {
            $params = $request->all();           
            $data = $this->postRepo->update($id, $params);
            $result = $this->postRepo->getMissionById($id);
            $results = array(
                'success' => true,
                'data' => $result,
                'message' => $this->msg->updateSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->updateError(),
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        } 
    }

    public function destroy($id)
    {
        try {           
            $this->postRepo->delete($id);
            $results = array(
                'success' => true,
                'message' => $this->msg->deleteSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->deleteError(),
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return response()->json($results);
        } 
    }
}
