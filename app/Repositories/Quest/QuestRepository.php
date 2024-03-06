<?php

namespace App\Repositories\Quest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\QuestRepositoryInterface;
use App\Models\Mission;
use App\Models\SubCategoryMission;
use App\Models\CategoryMission;
use App\Models\UserMission;
use App\Models\UserEquipment;
use App\Models\User;
use App\Helpers\Helpers;
use App\Helpers\Message;
use App\Repositories\BaseRepository;
class QuestRepository extends BaseRepository  implements QuestRepositoryInterface
{
    protected $modelCategory;
    protected $modelChapter;
    protected $model;
    protected $modelUserMission;
    protected $modelUserEquipment;
    protected $helpers;
    public function __construct(
        Helpers $helpers, 
        SubCategoryMission $SubCategoryMission, 
        CategoryMission $CategoryMission, 
        Mission $Mission,
        UserMission $UserMission,
        UserEquipment $UserEquipment
    ){
        $this->modelChapter     = $SubCategoryMission;
        $this->modelCategory    = $CategoryMission;
        $this->model            = $Mission;
        $this->modelUserMission = $UserMission;
        $this->modelUserEquipment = $UserEquipment;
        $this->helpers = $helpers;
    }
    public function getModel()
    {
        return Mission::class;
    }

    public function getAllChapter(){
        try {
            return $this->modelCategory->with(['sub_categories'])->first()->toArray();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**
     * chapter => sub category
     */
    public function getAllQuestByChapter( array $params ){
        try {
            $userData = Auth::user();
            $id_chapter = 1; //first chapter

            if ( isset($params) && isset($params['sub_category_id']) ) 
            {
                $id_chapter = $params['sub_category_id'];
            }   
            $data = $this->model->with(['childrents', 'userMissions'])
            ->where('sub_category_id', '=' , $id_chapter)
            ->where('level_id', '<=' , $userData->level)         
            ->where('parent_id', '=' , 0)
            ->get();
            return $data;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * create => quest by user id
     */
    public function createQuestByUser( array $params ){        
        return $this->modelUserMission->create($params);
    }
    /**
     * update => quest by user id
     */
    public function updateQuestByUser( array $params ){ 
        $userId = Auth::id(); 
        $dataUser = false;
        $results =  $this->modelUserMission->with(['mission' => function ($query){
            $query->select('id', 'equipment_id', 'gold', 'experience');
        }])
        ->whereIn('id', $params['id'])
        ->where('status',STATUS_QUEST['pending'])
        ->get()->toArray();

        if (count($results) > 0) {
            $gold = [];
            $exp = [];            
            // kiểm tra nhiệm vụ đã nhận có phần thưởng gold, exp, trang bị không để lấy thông tin lưu vào dữ liệu của User
            foreach ($results as $key => $value) {
                if ($value['mission']) {
                    $gold[] = $value['mission']['gold'];
                    $exp[] = $value['mission']['experience'];
                    if ($value['position_equipment']) {
                        $equipment = array(
                            'user_id' => $userId,
                            'equipment_id' => (int) $value['mission']['equipment_id'],
                            'position' => $value['position_equipment'],
                            'status' => 0
                        );
                        // thêm trang bị vào user nếu nhiệm vụ đó có phần thưởng
                        $this->helpers->createDataEquimentUser($equipment);
                        // thêm danh hiệu vào user nếu nhiệm vụ đó có phần thưởng
                        $medials = array(
                            'user_id' => $userId,
                            'medal_id' => (int) $value['mission']['equipment']['medal_id'],
                        );                                          
                        $this->helpers->createDataMedalUser($medials);
                    }  
                }
            }
            // update gold/exp khi nhận phần thưởng
            $dataUpdateUser = array(
                'gold' => 0,
                'experience' => 0,
            );
            if (count($gold) > 0) {
                $dataUpdateUser['gold'] = array_sum($gold);
            }
            if (count($exp) > 0) {
                $dataUpdateUser['experience'] = array_sum($exp);
            }            
            $dataUser = $this->helpers->updateDataUser($dataUpdateUser);
        }
        // update status;
        $data = $this->modelUserMission->whereIn('id', $params['id'])
        ->update([
            'status' => $params['status']
        ]);
        $resultData = false;
        if ($dataUser && $data) {
            $resultData = User::find($userId);
        }
        return $resultData;
    }
}
