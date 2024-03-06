<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Backend\Interfaces\BasePostRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Level;
use App\Models\Equipment;

class LevelController extends Controller
{
    /**
     * @var BasePostRepositoryInterface
     */
    protected $repo;
    protected $msg;
    protected $helper;

    public function __construct(BasePostRepositoryInterface $repo, Message $message, Helpers $helper, Level $model)
    {
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo = $repo;
        $this->repo->addModel($model);
    }
    
    public function index(Request $request)
    {
        try {            
            $params     = $request->all();
            $lists      = $this->repo->getAllLevel($params);
            $equipments = Equipment::all(['id', 'title'])->toArray();
            $results = array(
                'success' => true,
                'data' => $lists,
                'equipments' => $equipments,
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
        // $product = $this->repo->find($id);

        // return view('home.product', ['product' => $product]);
    }

    public function create(Request $request, Level $model)
    {
        try {
            $param = $request->all();
            if (isset($param['level']) && !empty($param['level'])) {
                # levelExist
                $existingLevel = $model->where('level', $param['level'])->first();
                if ($existingLevel) {
                    $results = array(
                        'success' => true,
                        'data' =>false,
                        'message' => $this->msg->levelExist(),
                        'status' => Response::HTTP_OK
                    );
                    return response()->json($results);
                }
            }
            $data = $this->repo->create($param);
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->createSuccess(),
                'status' => Response::HTTP_OK
            );
            return response()->json($results);
            
        } catch (\Throwable $th) {
            $results = array(
                //'message' => $this->msg->createError(),
                'message' => $th->getMessage(),
                'success' => false,
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function update(Request $request, $id, Level $model)
    {       
        try {
            $params = $request->all();           
            if (isset($params['level']) && !empty($params['level'])) {
                # levelExist
                $existingLevel = $model->where('level', $params['level'])->where('id','!=',$id)->first();
                if ($existingLevel) {
                    $results = array(
                        'success' => true,
                        'data' =>false,
                        'message' => $this->msg->levelExist(),
                        'status' => Response::HTTP_OK
                    );
                    return response()->json($results);
                }
            }
            $data = $this->repo->update($id, $params);
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
            $this->repo->delete($id);
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
