<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Backend\TypeEquipmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\TypeEquipment;
use App\Models\CategoryEquipment;

class TypeEquipmentController extends Controller
{
    /**
     * @var TypeEquipmentRepositoryInterface
     */
    protected $cateRepo;
    protected $msg;
    protected $helper;
    protected $constant;

    public function __construct(
        TypeEquipmentRepositoryInterface $cateRepo,
        Message $message,
        Helpers $helper,
        TypeEquipment $model,
        CategoryEquipment $modalCate
    )
    {
        $this->msg = $message;
        $this->helper = $helper;
        $this->cateRepo = $cateRepo;
        $this->cateRepo->addModel($model);
        $this->cateRepo->setModelCategory($modalCate);
    }
    
    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $lists = $this->cateRepo->list($params);
            $listCategories = $this->cateRepo->getCategories();
            $listPositions = EQUIPMENT_POSITION;
            $results = array(
                'success' => true,
                'data' => $lists,
                'categories' => $listCategories,
                'positions' => $listPositions,
                'message' => $this->msg->getSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        }           
    }

    public function show($id)
    {
        //
    }

    public function create(Request $request)
    {
        try {
            $param = $request->all();            
            $model = new TypeEquipment();
            $param['slug'] = $this->helper->getSlug($param['title'], $model);
            $data = $this->cateRepo->create($param);
            $result = $this->cateRepo->getPostById($data->id);
            $results = array(
                'success' => true,
                'data' => $result,
                'message' => $this->msg->createSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                'message' => $this->msg->createError(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function update(Request $request, $id)
    {       
        try {
            $params = $request->all();
            $data = $this->cateRepo->update($id, $params);
            $result = $this->cateRepo->getTypeById($data->id);
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
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function destroy($id)
    {
        try {           
            $this->cateRepo->delete($id);
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
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
