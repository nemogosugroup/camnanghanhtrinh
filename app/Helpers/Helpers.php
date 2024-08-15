<?php 
namespace App\Helpers;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use App\Helpers\Message;
use App\Models\User;
use App\Models\UserMedal;
use App\Models\Level;
use App\Models\UserEquipment;
use App\Models\UserMission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class Helpers {
    protected $msg;
    protected $modelUser;
    protected $modelLevel;
    protected $modelUserEquipment;
    protected $modelUserMedal;
    protected $modelUserMission;
    protected $ldapHost;  
    public function __construct(
        Message $message, 
        Level $modelLevel,
        User $modelUser,
        UserEquipment $modelUserEquipment,
        UserMedal $modelUserMedal,
        UserMission $modelUserMission
    ){
        $this->msg = $message;
        $this->ldapHost = env("LDAP_HOST", "222.255.168.250");
        $this->modelUser = $modelUser;
        $this->modelUserEquipment = $modelUserEquipment;
        $this->modelUserMedal = $modelUserMedal;
        $this->modelLevel = $modelLevel;
        $this->modelUserMission = $modelUserMission;
    }
    public function bindldap($email, $pass)
    {
        $ldaprdn = str_replace("@gosu.vn", "", $email) . "@gosu.vn";
        $ldappass = $pass;

        $ldapconn = ldap_connect($this->ldapHost) or die("Could not connect to LDAP server.");

        if ($ldapconn) {
            try {
                // Đặt các tùy chọn LDAP
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

                // Xác thực với tên người dùng và mật khẩu LDAP
                $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

                // Kiểm tra xem người dùng có xác thực thành công hay không
                ldap_close($ldapconn);

                return $ldapbind === true;
            } catch (\Exception $e) {
                ldap_close($ldapconn);
                return false;
            }
        }

        return false;
    }
    
    public function getSlug($title, $model) {
        $slug = Str::slug($title);
        $slugCount = count( $model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    /**
     * upload files
     */
    public function upLoadFiles($file){
        $url = false;
        $folderName = 'static/uploads/avatar';
        $path = public_path() . '/' . $folderName;
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $fullName   = $file->getClientOriginalName();
        $fullName   = $file->getClientOriginalName();
        $checkFiles = explode(".", $fullName);
        $title      = current( $checkFiles );
        $extension  = end( $checkFiles );
        $fileName   = Str::slug($title.time()).'.'.$extension;
        $file->move(public_path('static/uploads/avatar'), $fileName);
        $url = '/'.$folderName.'/'.$fileName;
        return $url;
    }

    /**
     * upload files
     */
    public function upLoadVuLanFiles($file){
        $folderPreview = 'static/vulan/uploads/preview-video/' . auth()->user()->id;
        if (File::exists($folderPreview)) {
            File::deleteDirectory($folderPreview);
        }

        $folderName = 'static/vulan/uploads/' . auth()->user()->id;
        $path = public_path() . '/' . $folderName;
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $fullName   = $file->getClientOriginalName();
        $checkFiles = explode(".", $fullName);
        $title      = current( $checkFiles );
        $extension  = end( $checkFiles );
        $fileName   = Str::slug($title.time()).'.'.$extension;
        $file->move(public_path('static/vulan/uploads/' . auth()->user()->id), $fileName);

        return '/'.$folderName.'/'.$fileName;
    }

    /**
     * upload files
     */
    public function upLoadVuLanPreviewVideo($file){
        $folderName = 'static/vulan/uploads/preview-video/' . auth()->user()->id;
        if (File::exists($folderName)) {
            File::deleteDirectory($folderName);
        }
        $path = public_path() . '/' . $folderName;
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
        $fullName   = $file->getClientOriginalName();
        $checkFiles = explode(".", $fullName);
        $title      = current( $checkFiles );
        $extension  = end( $checkFiles );
        $fileName   = Str::slug($title.time()).'.'.$extension;
        $file->move(public_path('static/vulan/uploads/preview-video/' . auth()->user()->id), $fileName);

        return '/'.$folderName.'/'.$fileName;
    }
    /**
     * function update gold/level/exp of user
     */
    public function updateDataUser( array $data ){
        $userId = Auth::id();
        $userData = $this->modelUser->with(['levels'])->find($userId);
        $currentLevel = (int) $userData->level;                
        if ($userData) {
            $totalExp = $data['experience'] + $userData['experience'];
            $totalGold = $data['gold'] + $userData['gold'];
            if ($userData->levels) {                
                $dataLevels = $userData->levels->toArray();
                $exp = $totalExp - $dataLevels['experience'];                
                if ($exp >= 0) {
                    $nextLevel = $currentLevel + 1;
                    // kiểm tra xem là level đã max hay chưa
                    $checkNextLevel = $this->modelLevel->find($nextLevel);
                    if ($checkNextLevel) {                        
                        $currentLevel = $nextLevel;
                        $totalExp = $exp;
                    }                    
                    $totalGold = $totalGold + $dataLevels['gold']; 
                    // kiểm tra khi update có phần thưởng hay là ko
                    if ( $dataLevels['equipment_id'] && $dataLevels['equipment_id'] > 0) {
                        $equipment = array(
                            'user_id' => $userId,
                            'equipment_id' => $dataLevels['equipment_id'],
                            'position' => $dataLevels['position_equipment'],
                            'status' => 0
                        );
                        $this->createDataEquimentUser( $equipment );
                    }                   
                }
            }
            //update data user
            $dataUpdate = array(
                'level' => $currentLevel,
                'gold' => $totalGold,
                'experience' => $totalExp,
            );
            $userData = $userData->update($dataUpdate);
        }
        return $userData;
    }
    /**
     * function update equipment of user
     */
    // public function createDataEquimentUser( array $data ){
    //     $checkData = $this->modelUserEquipment->where('user_id',$data['user_id'])->where('equipment_id',$data['equipment_id'])->first();
    //     if (!isset($checkData)) {
    //         $result = $this->modelUserEquipment->create($data);
    //         return $result;
    //     }
    //     return false;
    // }
    public function createDataEquimentUser( array $data ){
        return $this->modelUserEquipment->createOrNot($data);
    }
    /**
     * function update equipment of user
     */
    public function createDataMedalUser( array $data ){
       return $this->modelUserMedal->createOrNot($data);
    }
    /**
     * hoàn thành nhiệm vụ của user
     */
    public function questCompleteByMissionId($mission_id){        
        $userId = Auth::id();
        $data = array(
            'status' => STATUS_QUEST['pending'],
            'user_id' => $userId,
            'mission_id' => $mission_id // đây là Id cố định của nhiệm vụ cập nhập profile
        );
        return $this->modelUserMission->createOrNot($data);
    }

    public function getUserFlag(string $employeeId): int
    {
        $monthsProbationQty = 2;
        // If someone changed this value 20 to 21 I were dead
        $firstDayString = "20" . $employeeId;
        $year = substr($firstDayString, 0, 4);
        $month = substr($firstDayString, 4, 2);
        $day = substr($firstDayString, 6);
        $formattedDate = $year . "-" . $month . "-" . $day;

        return (int) Carbon::parse($formattedDate)->addMonth($monthsProbationQty)->lessThan(Carbon::now());
    }
}