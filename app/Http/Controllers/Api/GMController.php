<?php

namespace App\Http\Controllers\Api;

use App\Models\Api;
use App\Http\Controllers\Web\WebBaseController;
use App\Services\GMService;
use App\Models\MemberAPi;
use Illuminate\Support\Facades\DB;
use App\Models\Transfer;
use Illuminate\Support\Facades\Log;
use App\Models\GameRecord;
use App\Models\Member;

class GMController extends WebBaseController{
    
    protected $service;//,$api;
    public function __construct() {
        $this->service = new GMService();
//         $this->api = Api::where('api_name', 'GM')->first();
//         $this->username = "js11111";
//         $this->password = "123456";
    }
    
    public function index() {
        
        return view('test.api.gm.index', compact('api_list'));
        
    }
    
    public function register($username) {
        
        $member = $this->getMember();
        
        $member_api = $member->apis()->get();
        log::info("GMController:",["register.member_api"=>$member_api]);
        log::info("GMController:",["register.empty(member_api)"=>empty($member_api)]);
        if (empty($member_api)) {
            
            $res = $this->service->register($username);
            if ($res == "21") {
                return ["playerName"=>$username];
            }
            return $res;
        }else {
            return ["playerName"=>$username];
        }
        
    }
    
    /*
     * 登录游戏
     *
     */
    public function login($username,$api,$game_code,$mobile = "yes"){
        
        $res = $this->service->login($username,$api,$game_code);
        log::info("GMController:",["login.res"=>$res]);
        return $res["url"];
        
    }
    
    /**
     * 充值
     * @param unknown $username
     * @param unknown $password
     * @param unknown $amount
     * @param string $amount_type
     */
    public function deposit($username, $api_name,$amount){
        
        $member = $this->getMember();
        $member_api = $this->register_active($username, $api_name);
        
        
        $return = [
            'Code' => 0,
            'Message' => 'success',
            'url' => '',
            'Data' => '',
        ];
        
        if (!$member_api) {
            $return["Code"] = -1;
            $return['Message'] = "请联系管理员";
            return $return;
        }
        
        //判断余额
        if ($member->money < $amount) {
            $return['Code'] = -1;
            $return['Message'] = '账户余额不足';
            return $return;
        }
        
        try{
            DB::transaction(function() use($member_api,$api_name,$username, $amount,$member) {
                //个人账户
                $member->decrement('money' , $amount);
                //平台账户
                $member_api->increment('money', $amount);
                //修改api账号余额
                $api = Api::where('api_name', $api_name)->first();
                $api->decrement('api_money' , $amount);
                
                $res = $this->service->deposit($username,$api_name,$amount);
                log::info("GMController",["api_name"=>$api_name,"deposit.res"=>$res]);
                if (isset($res['playerName'])) {
                    //额度转换记录
                    Transfer::create([
                        'bill_no' => getBillNo(),
                        'api_type' => $member_api->api_id,
                        'member_id' => $member->id,
                        'transfer_type' => 0,
                        'money' => $amount,
                        'transfer_in_account' => $api_name.'账户',
                        'transfer_out_account' => '中心账户',
                        'result' => json_encode($res)
                    ]);
                } else {
                    DB::rollback();
                    $return['Code'] = $res;
                    $return['Message'] = '错误代码 '.$res.' 请联系客服';
                }
            });
        }catch(Exception $e){
            DB::rollback();
            $return['Code'] = 9999;
            $return['Message'] = '错误代码 9999 请联系客服';
        }
        
        
        return $return;
        
    }
    /**
     * 取现
     * @param unknown $username
     * @param unknown $password
     * @param unknown $amount
     * @param unknown $api
     */
    public function withdrawal($username, $api_name,$amount){
        
        $member = $this->getMember();
        $member_api = $this->register_active($username, $api_name);
        
        if (!$member_api) {
            $return["Code"] = -1;
            $return['Message'] = "请联系管理员";
            return $return;
        }
        
        if ($member_api->money < $amount) {
            $return['Code'] = -1;
            $return['Message'] = '余额不足';
            return $return;
        }
        
        
        $return = [
            'Code' => 0,
            'Message' => 'success',
            'url' => '',
            'Data' => '',
        ];
        log::info("GMController",["api_name"=>$api_name]);
        try{
            DB::transaction(function() use($member_api, $api_name,$username,$amount,$member) {
                //平台账户
                $member_api->decrement('money' , $amount);
                //个人账户
                $member->increment('money' , $amount);
                //修改api账号余额
                $api = Api::where('api_name', $api_name)->first();
                $api->increment('api_money' , $amount);
                $res = $this->service->withdrawal($username,$api_name,$amount);
                log::info("GMController",["api_name"=>$api_name,"deposit.res"=>$res]);
                if (isset($res['playerName'])) {
                    //额度转换记录
                    Transfer::create([
                        'bill_no' => getBillNo(),
                        'api_type' => $member_api->api_id,
                        'member_id' => $member->id,
                        'transfer_type' => 1,
                        'money' => $amount,
                        'transfer_in_account' => '中心账户',
                        'transfer_out_account' => $api_name.'账户',
                        'result' => json_encode($res)
                    ]);
                }else {
                    DB::rollback();
                    $return['Code'] = $res;
                    $return['Message'] = '错误代码 '.$res.' 请联系客服';
                }
            });
        }catch(\Exception $e){
            DB::rollback();
            log::info("GMController",["deposit.e"=>$e]);
            $return['Code'] = 9999;
            $return['Message'] = '错误代码 9999 请联系客服';
        }
        return $return;
    }
    
    /**
     * 余额
     * @param unknown $username
     * @param unknown $password
     * @param unknown $api
     */
    public function balance($username,$api_name){
        
        $member_api = $this->register_active($username, $api_name);
        
        if (!$member_api) {
            $return["Code"] = -1;
            $return['Message'] = "请联系管理员";
            return $return;
        }
        
        $res = $this->service->balance($username,$api_name);
        log::info("GMController:",["balance.res"=>$res]);
        $return = [
            'Code' => 0,
            'Message' => 'success',
            'url' => '',
            'Data' => '',
        ];
        
        if (isset($res['playerName'])) {
            $member_api->update([
                'money' => $res['balance']
            ]);
            $return['Data'] = $res['balance'];
        } else {
            $return['Code'] = $res;
            $return['Message'] = '查询余额失败！错误代码 '.$res.' 请联系客服';
        }
        return $return;
//         var_dump($res);
    }
    
    
    public function gameRecord () {
        
        
        $apis = Api::get();
        
        foreach ($apis as $api) {
            $startDate = GameRecord::where('api_type', $api->id)->max('recalcuTime');
            if (!$startDate) {
                $startDate = date("Y-m-d H:i:s",strtotime("-15 minute"));
                $endDate = date("Y-m-d H:i:s");
            }else {
                
                $endDate = date("Y-m-d H:i:s",strtotime($startDate,"+15 minute"));
            }
            
            $game_records = $this->service->game_record($api->api_name, $startDate, $endDate);
            
            foreach ($game_records as $game_record) {
                
//                 {
//                     "record_id": 4211,
//                     "player_name": "test0724",
//                     "transaction_id": "1092875466",
//                     "bet": 0.1,
//                     "win": 0.4,
//                     "ending_balance": 98.4,
//                     "jackpot_bet": 0,
//                     "jackpot_win": 0,
//                     "round_id": "544007560",
//                     "session_id": "4933774",
//                     "game_id": "eastereggs",
//                     "time": "2017-07-24 13:38:43",
//                     "platform": "GM"
//                 } 

                $PlayerName = $game_record["player_name"];
                $name = substr($PlayerName, $this->service->pre);
                $m = Member::where('name', $name)->first();
                
                GameRecord::create([
                    'billNo' => $game_record["record_id"],
                    'playerName' => $PlayerName,
                    'agentCode' => $game_record["AgentCode"],
                    'game_id' => $game_record["game_id"],
                    'agentCode' => $game_record["transaction_id"],
                    'remark' => $game_record["session_id"],
                    
                    'betTime' => date('Y-m-d H:i:s'),
                    'recalcuTime' => $game_record["time"],
                    'platformID' => $api->id,
                    'platformType' => $api->api_name,
                    
                    
                    
                    
                    'netAmount' => $game_record["game_id"],
                    'betAmount' => $betAmount,
                    'validBetAmount' => $value["ValidBetAmount"],
                    
                    'api_type' => $api->id,
                    'name' => $name,
                    'member_id' => $m?$m->id:0
                ]);
                
            }
            
        }
        
        
        
        
        
        
        
        
    }
    
    public function register_active($username, $api_name) {
        
        $member = $this->getMember();
        $api = Api::where('api_name', $api_name)->first();
        $member_api = $member->apis()->where('api_id', $api->id)->first();
//         log::info("GMController",["register_active.member_api"=>$member_api]);
        if (!$member_api) {
            
            $res = $this->register($username);
            log::info("GMController",["register_active.res"=>$res]);
            if (!isset($res['playerName'])) {
                return null;
            }  
            $res = $this->service->active($username, $api_name);   //激活$api_name平台
            log::info("GMController",["register_active.res"=>$res]);
            if (!isset($res['playerName'])) {
                return null;
            }
            //创建api账号
            $member_api = MemberAPi::create([
                'member_id' => $member->id,
                'api_id' => $api->id,
                'username' => $this->service->pre.$member->name,
                'password' => $this->service->password
            ]);
            
        }
        return $member_api;
    }
    
    public function active () {
        $username = $this->username;
        $api = "AG";
        $res = $this->service->active($username,$api);
        echo "balance:";
        var_dump($res);
    }
    
}