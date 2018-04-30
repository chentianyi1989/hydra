<?php
namespace App\Services;

use App\Models\Api;
use App\Services\CurlService;


class GMService {
    
    
    public $pre ;   // 玩家前缀
    public $domain;
    public $comId;
    public $comKey ;
    public $gamePlatform ;
    public $debug;
    public $salt ;
    public $currency;
    public $bettingProfileID;
    
    
    public $guid_json ;
    public $guidCode;
    public $SessionGUID;// 获取sessionguid
    
//     (测试环境 )
//     https://dynastyggroup.com/
//     http://api.dynastyggroup.com
//     (正式环境)
//     https://gmaster8.com/
//     https://api.gmaster8.com
    
    public function __construct() {
        $mod = Api::where('api_name', 'GM')->first();
        $this->pre = $mod->prefix;   // 玩家前缀
        $this->domain    = $mod->api_domain;
        $this->comId   = $mod->api_id;
        $this->comKey  = $mod->api_key;
        $this->gamePlatform = $mod->api_name;
        $this->debug = 0;
        $this->currency = 8;
        $this->bettingProfileID = 370;
    }
    
    
    /*
     * 创建账号  http://<domain>/api/mg/register.ashx
     */
    public function register($username,$password){
        
        $url = $this->domain."/register";
        echo "url:$url<br/>";
        $post_data = ["username"=>$username,"password"=>$password];
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }
    
    /*
     * 登录游戏
     * http://<API	domain>/<Platform>/game/open
     * 
     * $mobile： yes html5
     */
    public function login($username,$password,$api,$game_code,$mobile,$lang){
        $lang = "zh-CN";
        
        $url = "http://".$this->domain."/$api/game/open";
        
        $post_data = ['userName'=>$username,'password'=>$password,'game_code'=>$game_code,'lang'=>$lang];
        
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }
    
    /*
     * 存款 http://<API	domain>/<Platform>	/credit/deposit
     */
    public function deposit($username,$password,$amount,$api){
        
        $url = "http://".$this->domain."/$api/credit/deposit";
        $post_data = ['username'=>$username,'password'=>$password,'amount'=>$amount,];
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }
    
    /*
     * 提款 http://<API	domain>/<Platform>/credit/withdrawal
     */
    public function withdrawal($username,$password,$amount){
        
        $url = "http://".$this->domain."/api/mg/withdrawal.ashx";
        $post_data = array('username'=>$username,'password'=>$password,'amount'=>$amount);
        
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }
    
    /*
     * 查询玩家余额 http://<API	domain>/<Platform>/player/balance
     */
    public function balance($username,$password,$api){
        
        $url = "http://".$this->domain."/$api/player/balance";
        $post_data = array('username'=>$username,'password'=>$password);
        
        $receive = $this->send_post($url,$post_data);
        return $receive;
    }
    
    protected function send_post($url,$post_data) {
        
        $result = (new CurlService())->post($url, $post_data);
        return $result;
    }
    
}

