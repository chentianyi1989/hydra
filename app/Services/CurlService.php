<?php
namespace App\Services;

use Curl\Curl as Curl;
use Illuminate\Support\Facades\Log;
class CurlService{

    public function get($url,$data = [])
    {
        $curl = new Curl();
        //$curl->setBasicAuthentication('username', 'password');
//        $curl->setUserAgent('');
//        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        $curl->get($url,$data);

        if ($curl->error) {
            return $curl->error_code;
        }
        return $curl->response;
    }

    public function post($url, $data = [])
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setBasicAuthentication('supreme1_API', '12345678');//$curl->setBasicAuthentication('username', 'password');
//        $curl->setUserAgent('');
//        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        //$curl->setCookie('key', 'value');
        
        $curl->post($url, $data);
        Log::info("CurlService",["post.curl->request_headers"=>$curl->request_headers]);
//         var_dump($curl->request_headers);
        if ($curl->error) {
            return $curl->error_code;
        }
        
        return $curl->response;

//        var_dump($curl->request_headers);
//        var_dump($curl->response_headers);
    }

    public function put($url, $data = [])
    {
        $curl = new Curl();
        //$curl->setBasicAuthentication('username', 'password');
//        $curl->setUserAgent('');
//        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        //$curl->setCookie('key', 'value');
        $curl->put($url, $data);

        if ($curl->error) {
            return $curl->error_code;
        }
        return $curl->response;

//        var_dump($curl->request_headers);
//        var_dump($curl->response_headers);
    }

    public function patch($url, $data = [])
    {
        $curl = new Curl();
        //$curl->setBasicAuthentication('username', 'password');
//        $curl->setUserAgent('');
//        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        //$curl->setCookie('key', 'value');
        $curl->put($url, $data);

        if ($curl->error) {
            return $curl->error_code;
        }
        return $curl->response;

//        var_dump($curl->request_headers);
//        var_dump($curl->response_headers);
    }

    public function delete($url, $data = [])
    {
        $curl = new Curl();
        //$curl->setBasicAuthentication('username', 'password');
//        $curl->setUserAgent('');
//        $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        //$curl->setCookie('key', 'value');
        $curl->put($url, $data);

        if ($curl->error) {
            return $curl->error_code;
        }
        return $curl->response;

//        var_dump($curl->request_headers);
//        var_dump($curl->response_headers);
    }

}