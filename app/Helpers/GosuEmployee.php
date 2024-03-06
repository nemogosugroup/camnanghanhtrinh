<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Log;
use App\Helpers\GosuApi;
use App\Helpers\Message;
use Illuminate\Support\Facades\Cache;
class GosuEmployee
{
    
    protected $msg;
    protected $api;
    public function __construct(Message $message, GosuApi $api)
    {
        $this->msg = $message;
        $this->api = $api;
    }
    /**
     *  lấy thông tin của user từ erp
     */
    public function profile(array $data){       
        try {
            if (isset($data['email']) && !empty($data['email'])) {
                $params = array(
                    'email' => $data['email']
                );
                $data = $this->api->GosuGetData('v1/hrm/employee/employee-get-profile-by-email', $params);
            }else{
                $params = array(
                    'id' => $data['profile_id']
                );
                $data = $this->api->GosuGetData('v1/hrm/employee/employee-get-profile-by-id', $params);
            }            
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;
            if ($data->Code == 1) {
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = (isset($params['email']) && !empty($params['email'])) ? $this->toArray($data->Data) : $this->toArrySearchData($data->Data);
            }
            return $result;
        } catch (\Exception $e) {
            $result = array(
                'success'    => false,
                'error_code'=> $e->getCode(),
                'message'   => $e->getMessage()
            );
        }
        return $result;
    }
    /**
     * lấy thông tin đào tạo của user
     */
    public function training($profile_id){ 
        try {
            Cache::flush();
            $datatraining = Cache::get('training'); 
            $params = array(
                'id' => $profile_id
            );
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;
            if($datatraining){
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = unserialize($datatraining);
            }else{
                $data = $this->api->GosuGetData('v1/hrm/employee/training-by-id', $params);
                if ($data->Code == 1) {
                    $datatraining = $this->dataTraining($data->Data);
                    $result['success'] = true;
                    $result['message'] = $this->msg->getSuccess();
                    $result['data']    = $datatraining;
                    // thêm dữ liệu đào tạo của nhân sự vào cache tồn tại trong vòng một tháng
                    //Cache::put('training', $this->dataTraining($data->Data) , now()->addDays(10));
                    Cache::put('training', $this->dataTraining($data->Data) , now()->addMonth());
                }
            }
            return $result;
        } catch (\Exception $e) {
            $result = array(
                'success'    => false,
                'error_code' => $e->getCode(),
                'message'    => $e->getMessage()
            );
        }
        return $result;
    }
    /**
     * lấy danh sách nhân sự 
     */
    public function listEmployee(){
        try { 
            $listEmployee = [];
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;
            $dataEmployee = Cache::get('list_employee');            
            if($dataEmployee) {    
                $listEmployee = unserialize($dataEmployee);
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = $listEmployee;
            }else{
                $data = $this->api->GosuGetData('v1/hrm/employee/employee-lists', null); 
                if (isset($data->Code) && $data->Code == 1) {
                    foreach ($data->Data as $key => $value) {
                        $listEmployee[] = $this->toArrayEmployee($value);
                    }
                    // thêm list nhân sự vào cache tồn tại trong vòng một tháng
                    Cache::put('list_employee', serialize($listEmployee) , now()->addMonth());
                    $result['success'] = true;
                    $result['message'] = $this->msg->getSuccess();
                    $result['data']    = $listEmployee;
                }
            }           
            return $result;
        } catch (\Exception $e) {
            $result = array(
                'success'    => false,
                'error_code' => $e->getCode(),
                'message'    => $e->getMessage()
            );
        }
        return $result;
    }
    private function toArray($data){
        $obj = new \stdClass();
        $obj->profile_id    = $data->ProfileId;
        $obj->employee_id   = $data->EmployeeId;
        $obj->first_name    = $data->FirstName;
        $obj->last_name     = $data->LastName;
        $obj->avatar        = $data->AvatarUrl;
        $obj->gender        = $data->Gender == 'Nam' ? 1 : 0;
        $obj->birthday      = date("Y-m-d", strtotime( $data->DateOfBirth ));
        $obj->email         = $data->Email;
        $obj->area          = $data->AreaCompany;
        $obj->phone         = $data->Mobile;
        $obj->job           = $data->Job;
        $obj->dept          = $data->DeptId;
        $obj->department    = $data->Department;
        $obj->flag          = (isset($data->flag) && $data->flag > 1) ? 1 : 0;
        return get_object_vars($obj);
    }
    private function toArrySearchData($data){
        $obj = new \stdClass();
        $obj->profile_id    = $data->ProfileId;
        $obj->employee_id   = $data->EmployeeId;
        $obj->first_name    = $data->FirstName;
        $obj->last_name     = $data->LastName;
        $obj->avatar        = $data->AvatarUrl;
        $obj->gender        = $data->Gender == 'Nam' ? 1 : 0;
        $obj->birthday      = date("Y-m-d", strtotime( $data->DateOfBirth ));
        $obj->email         = $data->Email;
        $obj->area          = $data->AreaCompany;
        $obj->phone         = $data->Mobile;
        $obj->job           = $data->Job;
        $obj->dept          = $data->DeptId;
        $obj->department    = $data->Department;
        $obj->flag          = (isset($data->flag) && $data->flag > 1) ? 1 : 0;
        $obj->fullname      = $data->FirstName.' '.$data->LastName;
        $obj->experience    = 0;
        $obj->level         = 1;
        $obj->exp_level     = 100000000;
        return get_object_vars($obj);
    }

    private function dataTraining($data){
        $results = [];
        foreach ($data as $key => $value) {
            $results[] = $this->toArrayTraining($value);
        }
        return $results;
    }

    private function toArrayTraining($data){
        $obj = new \stdClass();
        $obj->starttime     = date("d-m-Y", strtotime( $data->StartTime ));
        $obj->endtime       = date("d-m-Y", strtotime( $data->EndTime ));
        $obj->training_at   = $data->TrainingAt;
        $obj->training_type = $data->TrainingTypeName;
        $obj->training_name = $data->OlogiesName;
        $obj->images        = false;
        return get_object_vars($obj);
    }

    private function toArrayEmployee($data){
        $obj = new \stdClass();
        $obj->profile_id    = $data->ProfileId;
        $obj->fullname      = $data->FullName;
        $obj->avatar        = $data->AvatarUrl;
        return get_object_vars($obj);
    }
}