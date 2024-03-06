<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\Backend\MedalRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Medal;
class MedalController extends Controller
{
    /**
     * @var MedalRepositoryInterface
     */
    protected $medalRepo;
    protected $msg;
    protected $helper;

    public function __construct(MedalRepositoryInterface $medalRepo, Message $message, Helpers $helper)
    {
        $this->medalRepo = $medalRepo;
        $this->msg = $message;
        $this->helper = $helper;
    }

    public function index(Request $request)
    {
        try {
            $params = $request->all();
            $lists = $this->medalRepo->getAllMedal($params);
            $listCategories = $this->medalRepo->getCategories();
            $results = array(
                'success' => true,
                'data' => $lists,
                'categories' => $listCategories,
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
        // $product = $this->medalRepo->find($id);

        // return view('home.product', ['product' => $product]);
    }

    public function create(Request $request)
    {
        try {
            $param = $request->all();
            $model = new Medal();
            $param['slug'] = $this->helper->getSlug($param['title'], $model);
            $data = $this->medalRepo->create($param);
            $result = $this->medalRepo->getPostById($data->id);
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
            $data = $this->medalRepo->update($id, $params);
            $result = $this->medalRepo->getPostById($id);
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
            $this->medalRepo->delete($id);
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
