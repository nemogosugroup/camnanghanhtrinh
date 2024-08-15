<?php
namespace App\Helpers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class GosuEmployee
{
    
    protected $msg;
    protected $api;
    protected $ERP_API_KEY;
    public function __construct(Message $message, GosuApi $api)
    {
        $this->msg = $message;
        $this->api = $api;
        $this->ERP_API_KEY = "30b58c32c3af4a9d49f116c27f863e76";
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
    private function toArrayEmployee($data){
        $obj = new \stdClass();
        $obj->profile_id    = $data->ProfileId;
        $obj->fullname      = $data->FullName;
        $obj->avatar        = $data->AvatarUrl;
        return get_object_vars($obj);
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
        $obj->level         = $data->Level;
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

    private function toArrayTraining($data): array
    {
        $obj = new \stdClass();
        $obj->starttime     = date("d-m-Y", strtotime( $data->StartTime ));
        $obj->endtime       = date("d-m-Y", strtotime( $data->EndTime ));
        $obj->training_at   = $data->TrainingAt;
        $obj->training_type = $data->TrainingTypeName;
        $obj->training_name = $data->OlogiesName;
        $obj->images        = false;
        return get_object_vars($obj);
    }
    /**
     * lấy thông tin đào tạo của user
     */
    public function training($profile_id, array $requests = []): array
    {
        try {
            Cache::flush();
            $dataTraining = Cache::get('training');
            $params = array(
                'ProfileId' => $profile_id,
                'Page' => $requests['page'] ?? 1,
                'PageSize' => $requests['limit'] ?? 10,
                'secrectKey' => $this->ERP_API_KEY,
            );
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;
            if($dataTraining){
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = unserialize($dataTraining);
            }else{
                $data = $this->api->GosuGetData('v1/training/library/course-list-of-user-api', $params);
                if ($data->Code == 1) {
                    $dataTraining = $this->toArrayTrainingList($data->Data);
                    $result['success'] = true;
                    $result['message'] = $this->msg->getSuccess();
                    $result['data']    = $dataTraining;
                    // thêm dữ liệu đào tạo của nhân sự vào cache tồn tại trong vòng một tháng
                    //Cache::put('training', $this->dataTraining($data->Data) , now()->addDays(10));
                    Cache::put('training', $this->toArrayTrainingList($data->Data) , now()->addMonth());
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
     * lấy thông tin đào tạo chi tiết của user
     */
    public function trainingDetail($profile_id, array $requests = []): array
    {
        try {
            $params = array(
                'ProfileId' => $profile_id,
                'id' => $requests['training_id'],
                'secrectKey' => $this->ERP_API_KEY,
            );
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;

            $data = $this->api->GosuGetData('v1/training/library/course-detail', $params);
            if ($data->Code == 1) {
                $dataTraining = $this->toArrayTrainingDetail($data->Data);
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = $dataTraining;
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
     * lấy thông tin thành viên
     */
    public function membersData(array $requests = []): array
    {
        try {
            $params = array(
                'deptId' => $requests['dept'],
                'secrectKey' => $this->ERP_API_KEY,
            );
            $result = array();
            $result['success'] = false;
            $result['message'] = $this->msg->getError();
            $result['data']    = false;

            $data = $this->api->GosuGetData('v1/hrm/employee/employee-lists-dept', $params);
            if ($data->Code == 1) {
                $dataMembers = $this->toArrayMembersData($data->Data ?? []);
                $result['success'] = true;
                $result['message'] = $this->msg->getSuccess();
                $result['data']    = $dataMembers;
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

    private function toArrayTrainingList($data): array
    {
        foreach ($data->List as $idx => $item) {
            $data->List[$idx] = collect($item)->toArray();
        }
        if (!empty($data->List)) {
            $items = collect($data->List);
            $paginator = new LengthAwarePaginator($items, $data->RowCount, $data->PageCount);
            $paginator->setPath(request()->root() . '/api/user/training-info');

            return $paginator->toArray();
        }

        return [];
    }

    private function toArrayTrainingDetail($data): array
    {
        $result = [];
        foreach ($data as $key => $item) {
            $result[$key] = $item;
        }
        // add videoType = check UrlVideo
        $result['videoType'] = str_contains($result['UrlVideo'], 'vimeo.com') ? 'vimeo' : 'stream';

        return $result;
    }

    private function toArrayMembersData(array $data): array
    {
        $managers = $tmpMangers = $employees = [];

        // get data from API
        foreach ($data as $item) {
            if ($item->Level < 7) {
                $managers[] = [
                    'tags' => ['Staff'],
                    'name' => $item->FullName,
                    'img' => $item->AvatarUrl,
                    'role' => $item->JobName,
                    'level' => $item->Level,
                ];
            } else {
                $employees[] = [
                    'tags' => ['Staff'],
                    'name' => $item->FullName,
                    'img' => $item->AvatarUrl,
                    'role' => $item->JobName,
                    'level' => $item->Level,
                ];
            }
        }

        // group user by level
        $levelMapping = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => []
        ];

        // MANAGERS
        foreach ($managers as $profile) {
            $level = $profile['level'];
            $fullName = $profile['name'];
            $levelMapping[$level][] = $fullName;
        }

        foreach ($levelMapping as $level => $names) {
            if ($names) $tmpMangers[$level] = $names;
        }

        $i = 1; $j = 0;
        foreach ($tmpMangers as $level => $members) {
            foreach ($managers as $idx => $member) {
                if ($member['level'] === $level) {
                    if(count($members) > 1) {
                        $managers[$idx]['id'] = $i + $j;
                        $j++;
                    } else {
                        $managers[$idx]['id'] = $i;
                    }
                    if ($i > 1) {
                        $managers[$idx]['pid'] = $i - 1;
                    }
                }
            }
            $i++;
        }

        // EMPLOYEES
        $i = 1;
        foreach ($employees as $idx => $employee) {
            $employees[$idx]['id'] = $i;
            $i++;
        }

        return [
            'managers' => $managers,
            'employees' => $employees,
        ];
    }
}