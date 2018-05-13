<?php
namespace App\Services;

use App\Models\Api;
use App\Services\CurlService;
use Illuminate\Support\Facades\Log;


class GMService {
    
    
    public $pre ;   // 玩家前缀
    public $password ;
    
//     public $debug;
    
    
    
//     (测试环境 )
//     https://dynastyggroup.com/
//     http://api.dynastyggroup.com
//     (正式环境)
//     https://gmaster8.com/
//     https://api.gmaster8.com
    
    public function __construct() {
        
        $this->pre = "sup";   // 玩家前缀
        $this->domain    = "https://api.gmaster8.com";
        $this->password = "123456";
    }
    
    
    /*
     * 创建账号  http://<domain>/api/mg/register.ashx
     */
    public function register($username){
        
        $url = $this->domain."/register";
        $post_data = ["username"=>$this->pre.$username,"password"=>$this->password];
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    /*
     * 登录游戏
     * http://<API	domain>/<Platform>/game/open
     * 
     * $mobile： yes html5
     */
    public function login($username,$api,$game_code,$mobile="yes",$lang="zh-CN"){
        
        $url = $this->domain."/$api/game/open";
        $post_data = ['username'=>$this->pre.$username,'game_code'=>$game_code,'lang'=>$lang];
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    /*
     * 存款 http://<API	domain>/<Platform>	/credit/deposit
     */
    public function deposit($username,$api,$amount){
        
        $url = $this->domain."/$api/credit/deposit";
        $post_data = ['username'=>$this->pre.$username,'amount'=>$amount,"externalTransactionId"=>getBillNo()];
//         log::info("GMService",["post_data"=>$post_data,"url"=>$url]);
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    /*
     * 提款 http://<API	domain>/<Platform>/credit/withdrawal
     */
    public function withdrawal($username,$api,$amount){
        
        $url = $this->domain."/$api/credit/withdrawal";
        $post_data = array('username'=>$this->pre.$username,'amount'=>$amount,"externalTransactionId"=>getBillNo());
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    /*
     * 查询玩家余额 http://<API	domain>/<Platform>/player/balance
     */
    public function balance($username,$api){
        
        $url = $this->domain."/$api/player/balance";
        $post_data = array('username'=>$this->pre.$username);
        log::info("GMService:",["balance.username"=>$this->pre.$username]);
        log::info("GMService:",["balance.url"=>$url]);
        $receive = $this->send_post($url,$post_data);
        log::info("GMService:",["balance.receive"=>$receive]);
        return json_decode($receive, TRUE);
    }
    
    /*
     * 激活平台 ：<API	domain>/<Platform>/player/active
     */
    public function active ($username,$api) {
        $url = $this->domain."/$api/player/active";
        $post_data = array('username'=>$this->pre.$username);
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    public function game_record ($api,$fromDate,$toDate) {
        $url = $this->domain."/$api/game/history";
        $post_data = array('fromDate'=>$fromDate,'toDate'=>$toDate);
        $receive = $this->send_post($url,$post_data);
        return json_decode($receive, TRUE);
    }
    
    protected function send_post($url,$post_data) {
        
        $result = (new CurlService())->post($url, $post_data);
        return $result;
    }
    
}

