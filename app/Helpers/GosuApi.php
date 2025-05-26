<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Log;

class GosuApi
{
    protected $url;
    public function __construct()
    {        
        $this->url = env('APP_ENV') == 'local' ? 'http://localhost:27698/' : 'https://apis.gosu.vn/';       
    }
    public function GosuCURL($url, $method = 'GET', $data = null)
    {
        $ch = curl_init();

        if ($ch === false) {
            return null;
        }        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Thiết lập phương thức
        $method = strtoupper($method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Nếu có data và là POST/PUT, thì encode JSON và set body
        if (in_array($method, ['POST', 'PUT']) && $data !== null) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ]);
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            // In lỗi nếu có
            echo 'cURL Error: ' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response);
    }

    public function GosuGetData( $get_url, $params = null){       
        try {
            $url = $this->url.$get_url;
            if(is_array($params)){
                $count = 0;
                foreach ($params as $key => $value) {    
                    if($count==0){
                        $url .='?'.$key.'='.$value;
                    }else{
                        $url .= '&'.$key.'='.$value;
                    }
                    $count++;
                }    
            }
            $data = $this->GosuCURL($url, 'GET');
            return $data;
        } catch (\Exception $e) {
            $results = array(
                'success'    => false,
                'status'     => 500,
                'mgs'        => $e->getMessage(),
            );
            return $results;
        }
    }

    public function GosuPostData( $get_url, $data){
        try {
            $url = $this->url.$get_url;
            $results = $this->GosuCURL($url, 'POST', $data);
            return $results;
        } catch (\Exception $e) {
            $results = array(
                'success'    => false,
                'status'     => 500,
                'mgs'        => $e->getMessage(),
            );
            return $results;
        }
    }
}