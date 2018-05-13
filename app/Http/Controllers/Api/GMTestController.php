<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Http\Controllers\Web\WebBaseController;
use App\Services\GMService;

class GMTestController extends WebBaseController{
    
    protected $service,$api;
    public function __construct() {
        $this->service = new GMService();
        $this->api = Api::where('api_name', 'GM')->first();
        $this->username = "js11111";
        $this->password = "123456";
    }
    
    public function index() {
        
        
        return view('test.api.gm.index', compact('api_list'));
        
    }
    
    public function register() {
        
        $username = $this->username;
        $password = $this->password;
        $res = $this->service->register($username, $password);
        
        echo "register:";
        var_dump($res);
    }
    
    /*
     * 登录游戏
     *
     */
    public function login($game_code){
        
        
        $mobile = "yes";
        $api = "AG";
        $username = $this->username;
        $password = $this->password;
        $game_code = "";
        
        $res = $this->service->login($username,$password,$api,$game_code,$mobile,"");
        echo "login:";
        var_dump($res);
    }
    
    /**
     * 充值
     * @param unknown $username
     * @param unknown $password
     * @param unknown $amount
     * @param string $amount_type
     */
    public function deposit(){
        
        $username = $this->username;
        $password = $this->password;
        $amount = 1;
        $api = "AG";
        
        $res = $this->service->deposit($username,$password,$amount,$api);
        echo "deposit:";
        var_dump($res);
    }
    /**
     * 取现
     * @param unknown $username
     * @param unknown $password
     * @param unknown $amount
     * @param unknown $api
     */
    public function withdrawal(){
        $username = $this->username;
        $password = $this->password;
        $amount = 1;
        $api = "AG";
        $res = $this->service->withdrawal($username,$password,$amount,$api);
        echo "withdrawal:";
        var_dump($res);
    }
    
    /**
     * 余额
     * @param unknown $username
     * @param unknown $password
     * @param unknown $api
     */
    public function balance($username,$api){
        $username = $this->username;
//         $password = $this->password;
        $api = "AG";
        $res = $this->service->balance($username,$api);
        echo "balance:";
        var_dump($res);
    }
    
    public function active () {
        $username = $this->username;
        $api = "AG";
        $res = $this->service->active($username,$api);
        echo "balance:";
        var_dump($res);
    }
    
}