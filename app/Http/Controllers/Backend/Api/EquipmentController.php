<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use App\Models\Medal;
use App\Models\TypeEquipment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    /**
     * @var EquipmentRepositoryInterface
     */
    protected $repo;
    protected $repoType;
    protected $repoMedal;
    protected $msg;
    protected $helper;

    public function __construct(
        EquipmentRepositoryInterface $repo,
        EquipmentRepositoryInterface $repoType,
        EquipmentRepositoryInterface $repoMedal,
        Message $message,
        Helpers $helper,
        Equipment $equipment,
        TypeEquipment $type,
        Medal $medal
    )
    {
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo = $repo;
        $this->repoType = $repoType;
        $this->repoMedal = $repoMedal;
        $this->repo->addModel($equipment);
        $this->repoType->addModel($type);
        $this->repoMedal->addModel($medal);
    }
    
    public function index(Request $request)
    {
        try {            
            $params = $request->all();
            $lists = $this->repo->getAllEquipment($params);
            $listTypes = $this->repoType->getTypes();
            $listMedals = $this->repoMedal->getMedals();
            $listPositions = EQUIPMENT_POSITION;
            $results = array(
                'success' => true,
                'data' => $lists,
                'types' => $listTypes,
                'medals' => $listMedals,
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
            $model = new Equipment();
            $param['slug'] = $this->helper->getSlug($param['title'], $model);
            $data = $this->repo->create($param);
            $data = $this->repo->getEquipmentById($data->id);
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->createSuccess(),
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

    public function update(Request $request, int $id)
    {       
        try {
            $params = $request->all();
            $this->repo->update($id, $params);
            $result = $this->repo->getEquipmentById($id);
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
                'status' => Response::HTTP_OK
            );
            return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
