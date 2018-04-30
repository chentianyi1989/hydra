<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Http\Controllers\Web\WebBaseController;
use App\Services\GMService;

class GMController extends WebBaseController{
    
    protected $service,$api;
    public function __construct() {
        $this->service = new GMService();
        $this->api = Api::where('api_name', 'GM')->first();
        $this->username = "js11111";
        $this->password = "123456";
    }
    
    public function index() {
        echo "index";
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
    public function login($username,$password,$game_code){
        
        
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
    public function deposit($username, $password, $amount){
        
    }
    
    
}