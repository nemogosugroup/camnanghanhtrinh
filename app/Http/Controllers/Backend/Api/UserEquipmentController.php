<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;
use App\Repositories\Interfaces\Backend\UserEquipmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\UserEquipment;

class UserEquipmentController extends Controller
{
    /**
     * @var UserEquipmentRepositoryInterface
     */
    protected $repo;
    protected $repoEquipment;
    protected $msg;
    protected $helper;

    public function __construct(
        UserEquipmentRepositoryInterface $repo,
        EquipmentRepositoryInterface $repoEquipment,
        Message $message,
        Helpers $helper,
        UserEquipment $model,
        Equipment $modelEquipment
    )
    {
        $this->repo = $repo;
        $this->repoEquipment = $repoEquipment;
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo->addModel($model);
        $this->repoEquipment->addModel($modelEquipment);
    }

    public function addEquipment(Request $request)
    {
        try {
            $params = $request->all();
            $exists = $this->repo->exists($params);
            if ($exists) {
                $results = array(
                    'message' => $this->msg->dataExisted(),
                    'success' => false,
                    'status' => Response::HTTP_OK
                );
                return response()->json([$results],Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $equipment = $this->repoEquipment->getPostById($params['equipment_id']);
            $params['position'] = $equipment['type']['position'];
            $params['status'] = 0;
            $data = $this->repo->create($params);
            $results = array(
                'success' => true,
                'data' => $data,
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
}
