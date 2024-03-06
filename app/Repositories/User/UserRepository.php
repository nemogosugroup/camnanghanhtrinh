<?php

namespace App\Repositories\User;
use App\Repositories\Medal\UserMedalRepository;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\Backend\UserEquipmentRepositoryInterface;
//use App\Repositories\BaseRepository;
use App\Models\User;
use App\Helpers\GosuApi;
use App\Helpers\GosuEmployee;
use App\Helpers\Helpers;
use App\Helpers\Message;
use App\Models\Acl;
use App\Models\Role;
use App\Models\UserCourse;
use App\Models\UserEquipment;
use App\Models\UserMission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserRepository implements UserRepositoryInterface
{
    
    protected $gosuEmployee;
    protected $msg;
    protected $helpers;
    protected $userEquipmentRepo;
    public function __construct(
        GosuEmployee $gosuEmployee, 
        Message $message, 
        Helpers $helpers,  
        UserEquipmentRepositoryInterface $userEquipmentRepo,
        UserEquipment $modelUserEquipment
    ){
        $this->gosuEmployee  = $gosuEmployee;
        $this->msg = $message;
        $this->helpers = $helpers;
        $this->userEquipmentRepo = $userEquipmentRepo;
        $this->userEquipmentRepo->addModel($modelUserEquipment);
    }
    public function getAllUsers(array $params) 
    {
        $user = User::with('roles');
        ## Search by name ##
        if ($params['name'] != null) {
            $user->where('first_name', 'Like', '%' . $params['name'] . '%');
            $user->orWhere('last_name', 'Like', '%' . $params['name'] . '%');
        }
        ## Search by email ##
        if ($params['email'] != null) {
            $user->where('email', 'Like', '%' . $params['email'] . '%');
        }
        $data = $user->latest()->paginate($params['limit']);
        return $data->toArray();
    }

    public function getUserById($id) 
    {
        return User::with('roles')->findOrFail($id);
    }

    public function getUserByProfileId($pId)
    {
        return User::query()->where('profile_id', $pId)->first();
    }

    public function deleteUser($id) 
    {
        User::destroy($id);
    }

    public function createUser(array $userDetails) 
    {
        $userDetails['email_verified_at'] = now();
        $userDetails['remember_token'] = Str::random(10);
        $user = User::create($userDetails);
        $roleUser = Role::findByName(Acl::ROLE_USER);
        $roleSuperAdmin = Role::findByName(Acl::ROLE_SUPER_ADMIN);
        $roleAdmin = Role::findByName(Acl::ROLE_ADMIN);
        if ($userDetails['email'] == 'phe.tran@gosu.vn') {
            $user->syncRoles($roleSuperAdmin, $roleAdmin, $roleUser);
        }else{
            $user->syncRoles($roleUser);
        }
        
        return $user;
    }

    public function updateUsers(array $updateUsers) 
    {
        $user = User::findOrFail($updateUsers['id']);
        if ($user) {
            if(isset($updateUsers['roles']))
                $user->syncRoles($updateUsers['roles']);
            $user->update($updateUsers['field']);
        }
        if(isset($updateUsers['check'])) {
            $user->password = Hash::make($updateUsers['password']);
            $user->save();
        }
        if (!empty($updateUsers['field']['avatar']) || !empty($updateUsers['field']['achievements'])) {
            //Nhiệm vụ cập nhập profile
            $mission_id = 21;
            $result = $this->helpers->questCompleteByMissionId($mission_id);
        }
        return $this->getUserById($updateUsers['id']);
    }

    public function updateRoles($id, array $roles)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($roles);
        return $user;
    }

    public function exportUsers() {
        $user = User::all();
        return $user->toArray();
    }

    public function loginUser(array $data){
        $email = $data['email'];
        $password = $data['password'];
        if (last(explode("@", $email)) != 'gosu.vn') {
            $results = array(
                'message' => $this->msg->emaiError(),
                'data' => false,
                'status' => Response::HTTP_OK
            );
            return $results;
        }
        if($email == 'admin@gosu.vn'){
            if(Auth::attempt(['email' => $email, 'password' => $password])){
                $authUser = Auth::user();
                $roles = $authUser->roles->pluck('name');
                $data = $authUser->toArray();
                $data['access_token'] =  $authUser->createToken($authUser['email'])->plainTextToken; 
                $data['roles'] = $roles;
                $data['training'] = false;
                $results = array(
                    'message' => $this->msg->loginSuccess(),
                    'data' => $data,
                    'success' => true,
                    'status' => Response::HTTP_OK
                );
                $dataMedal = app(UserMedalRepository::class)->getUserMedalData();
                $results['data_medal'] = $dataMedal;
                //Nhiệm vụ cập nhập đăng nhập
                $mission_id = 20;
                $resultQuest = $this->helpers->questCompleteByMissionId($mission_id);
            }else{
                $results = array(
                    'message' => $this->msg->passError(),
                    'data' => false,
                    'success' => false,
                    'status' => Response::HTTP_OK
                );
            }
        }else{
            //login ldap
            $check_pass = $this->helpers->bindldap($email, $password);
            if(!$check_pass){
                $results = array(
                    'message' => $this->msg->loginError(),
                    'data' => false,
                    'status' => Response::HTTP_OK
                );
                return $results;
            }
            if(!Auth::attempt(['email' => $email, 'password' => $password])){
                $params = array('email' => $email);
                $profile = $this->gosuEmployee->profile($params);
                if (!$profile['success']) {
                    $results = array(
                        'message' => $this->msg->loginError(),
                        'data' => false,
                        'status' => Response::HTTP_OK
                    );
                    return $results;
                }
                $user = User::where('email', $email)->first();
                if($user){
                    $user->password = Hash::make($password);
                    $user->save();
                }else{
                    $user = $profile['data'];
                    $user['password'] = Hash::make($password);
                    $this->createUser($user);
                }
            }
            if(Auth::attempt(['email' => $email, 'password' => $password])){
                $authUser = Auth::user();
                $roles = $authUser->roles->pluck('name');
                $data = $authUser->toArray();                
                $data['access_token'] =  $authUser->createToken($authUser['email'])->plainTextToken; 
                $data['roles'] = $roles;
                // $data['courses'] = $authUser->courses->toArray();
                // $data['equipments'] = $authUser->equipments->toArray();
                $profileId = $data['profile_id'];
                //$profileId = 252;
                $training = $this->gosuEmployee->training( $profileId );
                $data['training'] = false;
                if($training['success']){
                    $data['training'] = $training['data'];
                }
                $results = array(
                    'message' => $this->msg->loginSuccess(),
                    'data' => $data,
                    'success' => true,
                    'status' => Response::HTTP_OK
                );
            }
            //Nhiệm vụ cập nhập đăng nhập
            $mission_id = 20;
            $resultQuest = $this->helpers->questCompleteByMissionId($mission_id);
            $dataMedal = app(UserMedalRepository::class)->getUserMedalData();
            $results['data_medal'] = $dataMedal;
        }
        return $results;
    }
    public function infoUser() {
        $authUser = Auth::user();
        $roles = $authUser->roles->pluck('name');
        $data = $authUser->toArray();
        // $data['courses'] = $authUser->courses;
        // $data['equipments'] = $authUser->equipments;
        $data['access_token'] = $authUser->createToken($authUser['email'])->plainTextToken;
        $data['roles'] = $roles;
        $profileId = $data['profile_id'];
        //$profileId = 252;
        //$profileId = 279;
        $training = $this->gosuEmployee->training( $profileId );
        if($training['success']){
            $data['training'] = $training['data'];
        }
        $dataMedal = app(UserMedalRepository::class)->getUserMedalData();
        $results = array(
            'message' => $this->msg->getSuccess(),
            'data' => $data,
            'data_medal' => $dataMedal,
            'success' => true,
            'status' => Response::HTTP_OK
        );
        return $results;
    }

    // lấy danh sách nhân sự đang làm việc
    public function listEmployee()
    {
        $results = $this->gosuEmployee->listEmployee();
        return $results;
    }
    
    // tìm kiếm thông tin nhân sự
    public function store(array $params)
    {     
        //$params['profile_id'] = 1;
        if( $params['profile_id'] ){
            $user = User::query();
            $data = $user->where('profile_id', $params['profile_id'])->first();
            if (empty($data) && !isset($data)) {
                $results = $this->gosuEmployee->profile($params);
                if (isset($results['success']) && $results['success']) {
                    $data = $results['data'];
                }
            } 
        } 
                
        $results = array(
            'message' => $this->msg->getSuccess(),
            'data' => false,
            'equipped' => [],
            'success' => false,
            'status' => Response::HTTP_OK
        );           
        if(isset($data)){
            $equippedItems = false;
            if (isset($data['id'])) {
                $equippedItems = $this->userEquipmentRepo->getEquippedItemsByUserId($data['id']);
            }
            $results = array(
                'message' => $this->msg->getSuccess(),
                'data' => $data,
                'equipped' => $equippedItems,
                'success' => true,
                'status' => Response::HTTP_OK
            );
        }
        return $results;
    }

    // mua khoá học hoặc mua trang bị
    public function addCourseEquipment(array $params){
        $results = array(
            'message' => $this->msg->buyCourseError(),
            'data' => false,
            'success' => false,
            'status' => Response::HTTP_OK
        ); 
        if(!$params || !$params['type'] || !$params['item_id'])
            return $results;
        $user = Auth::user();
        switch ($params['type']) {
            case TYPE_MARKET['course']:
                $fields = array(
                    'user_id' => $user->id,
                    'course_id' => $params['item_id'],
                );
                $data = UserCourse::create($fields);
                if($data){
                    $gold = $params['gold']; 
                    $user->update(['gold'=>$gold]);
                    $results['message'] = $this->msg->buyCourseSuccess();
                    $results['data'] = $data;
                    $results['success'] = true;
                }
                break;
            case TYPE_MARKET['equipment']:
                $fields = array(
                    'user_id' => $user->id,
                    'equipment_id' => $params['item_id'],
                    'position' => $params['position'],
                    'status' => 0,
                );
                $data = UserEquipment::create($fields);
                if($data){
                    $gold = $params['gold']; 
                    $user->update(['gold'=>$gold]);
                    $results['message'] = $this->msg->buyEquipmentSuccess();
                    $results['data'] = $data;
                    $results['success'] = true;
                }
                break;
            default:
                break;
        }
        return $results;
    }

    // lấy khoá học/trang bị 
    public function getCourseEquipment( array $params ) {
        $results = array(
            'message' => $this->msg->getError(),
            'data' => false,
            'success' => false,
            'status' => Response::HTTP_OK
        ); 
        if(!$params || !$params['type'])
            return $results;
        $user = Auth::user();
        switch ($params['type']) {
            case TYPE_MARKET['course']:
                $data = $user->courses;
                if($data){ 
                    $results['message'] = $this->msg->getSuccess();
                    $results['data'] = $data;
                    $results['success'] = true;
                }
                break;
            case TYPE_MARKET['equipment']:
                $data = $user->equipments;
                if($data){ 
                    $results['message'] = $this->msg->getSuccess();
                    $results['data'] = $data;
                    $results['success'] = true;
                }
                break;
            default:
                break;
        }
        return $results;
    }
}
