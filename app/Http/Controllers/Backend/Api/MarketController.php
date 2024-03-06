<?php

namespace App\Http\Controllers\Backend\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\Backend\CourseRepositoryInterface;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\Course;
use App\Models\Equipment;
use App\Models\TypeEquipment;
use App\Models\CategoryCourse;
class MarketController extends Controller
{
    /**
     * @var CourseRepositoryInterface
     */
    protected $repo;
    protected $repoEquipment;
    protected $msg;
    protected $helper;

    public function __construct(
        CourseRepositoryInterface $repo, 
        Message $message, 
        Helpers $helper, 
        CategoryCourse $modelCategory, 
        Course $model,
        EquipmentRepositoryInterface $repoEquipment,
        Equipment $modelEquipment,
        TypeEquipment $modelTypeEquipment
    )
    {
        $this->repo = $repo;
        $this->repo->addModel($model);
        $this->repo->setModelCategory($modelCategory);
        $this->repoEquipment = $repoEquipment;
        $this->repoEquipment->addModel($modelEquipment);
        $this->repoEquipment->setModelCategory($modelTypeEquipment);
        $this->msg = $message;
        $this->helper = $helper; 
        
    }

    public function index(Request $request)
    {
        try {
            $authUser = Auth::user();
            $params = $request->all();
            $lists = $this->repo->getAllPost($params);
            $listCategories = $this->repo->getCategories();
            $courses = false;
            $equipments = false;
            if(isset($params['type']) && $params['type'] == TYPE_MARKET['equipment']){
                $lists = $this->repoEquipment->getAllPost($params);
                $listCategories = $this->repoEquipment->getCategories();
                if ($params['type'] == TYPE_MARKET['equipment']) {
                    $equipments = $authUser->equipments->toArray();
                }else{
                    $courses = $authUser->courses->toArray();
                }
            }
            $results = array(
                'success' => true,
                'data' => $lists,
                'categories' => $listCategories,
                'courses' => $courses,
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
}
