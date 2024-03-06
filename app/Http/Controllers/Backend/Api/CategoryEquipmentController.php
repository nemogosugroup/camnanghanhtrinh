<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\CategoryEquipment;

class CategoryEquipmentController extends Controller
{
    /**
     * @var EquipmentRepositoryInterface
     */
    protected $cateRepo;
    protected $msg;
    protected $helper;

    public function __construct(EquipmentRepositoryInterface $cateRepo, Message $message, Helpers $helper, CategoryEquipment $model)
    {
        $this->cateRepo = $cateRepo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->cateRepo->addModel($model);
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $lists = $this->cateRepo->getAllCategories($params);
            $results = array(
                'success' => true,
                'data' => $lists,
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
            $model = new CategoryEquipment();
            $param['slug'] = $this->helper->getSlug($param['title'], $model);
            $data = $this->cateRepo->create($param);
            $result = $this->cateRepo->find($data->id);
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
            $results = array(
                'success' => true,
                'data' => $data,
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
