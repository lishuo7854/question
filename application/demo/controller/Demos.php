<?php
/**
 * Created by PhpStorm.
 * User: 78548_000
 * Date: 2018/4/7
 * Time: 17:36
 */

namespace app\demo\controller;

use think\Request;
use think\Db;
use think\Cache;
use \think\Controller;
use \think\View;
use app\demo\model\Info;
use  app\demo\model\Difficult;
use  app\demo\model\Easy;
use  app\demo\model\Question;
use  app\demo\model\Middle;
use app\demo\model\Score;
use  app\demo\model\User;
require("classes/yb-globals.inc.php");
class Demos extends \think\Controller
{


    public function demo()
    {
        $config = array(
            'AppID' => 'c48e1e6879902d33',     //此处填写你的appid
            'AppSecret' => 'c5dcd4c27b9e545347cb78d4235338eb',     //此处填写你的AppSecret
            'CallBack' => 'http://f.yiban.cn/iapp219077',     //此处填写你的授权回调地址
        );
        $api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
        $iapp = $api->getIApp()->perform();
        $token = $iapp['visit_oauth']['access_token'];//轻应用获取的token

//        取出用户数据
        $me_url = 'https://openapi.yiban.cn/user/verify_me?access_token=' . $token;
        $user_data = file_get_contents($me_url);
        $user_data_arr = json_decode($user_data, true);
//        设置缓存
        Cache::set('user', $user_data_arr['info']);
        $user_yb_id = $user_data_arr['info']["yb_userid"];
//        查询用户数据是否在数据库
        $user_exist = Db::table('user')->where('yb_userid', $user_yb_id)->select();
        if (count($user_exist) == 0) {
            Db::table('user')->data([
                'yb_userid' => $user_data_arr['info']["yb_userid"],
                'yb_realname' => $user_data_arr['info']["yb_realname"],
                'yb_schoolid' => $user_data_arr['info']["yb_schoolid"],
                'yb_schoolname' => $user_data_arr['info']["yb_schoolname"],
                'yb_collegename' => $user_data_arr['info']["yb_collegename"],
                'yb_classname' => $user_data_arr['info']["yb_classname"],
                'yb_enteryear' => $user_data_arr['info']["yb_enteryear"],
                'yb_studentid' => $user_data_arr['info']["yb_studentid"],
                'yb_examid' => $user_data_arr['info']["yb_examid"],
                'yb_admissionid' => $user_data_arr['info']["yb_admissionid"],
                'yb_employid' => $user_data_arr['info']["yb_employid"]])->insert();
        } else {
            dump($user_exist[0]['id']);
            $user_score = Db::table('score')->where('user_id',7)->find();
            dump(count($user_score));
            if ($user_score == NULL){
                dump('不存在开始答题');
            }else{
                $this->redirect('demo/Index/user_rank');
                dump('已经存在,跳转');
            }

        }
        //            题目
        $arr = [];
        for ($i = 1; $i < 10; $i++) {
            $data = Easy::get(rand(2, 676));
            array_push($arr, $data);
            $unique_arr = array_unique($arr);
            if (count($unique_arr) >= 10) {
                break;
            }
        }
        for ($i = 1; $i < 10; $i++) {
            $data = Difficult::get(rand(2, 525));
            array_push($arr, $data);
            $unique_arr = array_unique($arr);
            if (count($unique_arr) >= 20) {
                break;
            }
        }
        for ($i = 1; $i < 20; $i++) {


            $data = Middle::get(rand(2, 503));
            array_push($arr, $data);
            $unique_arr = array_unique($arr);
            if (count($unique_arr) >= 40) {
                break;
            }
        }

//        第一次加载的数据
        $first_data = array(
            'problem' => $arr
        , 'user' => $user_data_arr);
       return  json($first_data);
    }



    public function get_code()
    {
        $config = array(
            'AppID' => 'c48e1e6879902d33',     //此处填写你的appid
            'AppSecret' => 'c5dcd4c27b9e545347cb78d4235338eb',     //此处填写你的AppSecret
            'CallBack' => 'http://f.yiban.cn/iapp219077',     //此处填写你的授权回调地址
        );
        $appUrl = isset($config['CallBack']) ? $config['CallBack'] : 'javaScript:;';
        $this->redirect($appUrl);
    }


}





class YBOpenApi
{
    const YIBAN_OPEN_URL = "https://openapi.yiban.cn/";//

    private static $mpInstance = NULL;

    private $_config = array(
        'appid' => '',
        'seckey' => '',
        'token' => '',
        'backurl' => ''
    );

    private $_instance = array();

    /**
     * 取YBOpenApi实例对象
     *
     * 单例，其它的配置参数使用init()或bind()方法设置
     */
    public static function getInstance()
    {
        if (self::$mpInstance == NULL) {
            self::$mpInstance = new self();
        }

        return self::$mpInstance;
    }

    /**
     * 构造函数
     *
     * 使用 YBOpenApi::getInstance() 初始化
     */
    private function __construct()
    {

    }

    /**
     * 初始化设置
     *
     * YBOpenApi对象的AppID、AppSecret、回调地址参数设定
     *
     * @param   String 应用的APPID
     * @param   String 应用的AppSecret
     * @param   String 回调地址
     * @return  YBOpenApi 自身实例
     */
    public function init($appID, $appSecret, $callback_url = '')
    {
        $this->_config['appid'] = $appID;
        $this->_config['seckey'] = $appSecret;
        $this->_config['backurl'] = $callback_url;

        return self::$mpInstance;
    }

    /**
     * 设定访问令牌
     *
     * 如果已经取到访问令牌，使用此方法设定
     * 大多的接口只需要访问令牌即可完成操作
     * 这类接口不需要调用init()方法
     *
     * @param   String 访问令牌
     * @return  YBOpenApi 自身实例
     */
    public function bind($access_token)
    {
        $this->_config['token'] = $access_token;

        return self::$mpInstance;
    }

    /**
     * HTTP请求辅助函数
     *
     * 对CURL使用简单封装，实现POST与GET请求
     *
     * @param   String api接口地址
     * @param   Array 请求参数数组
     * @param   Boolean 是否使用POST方式请求,默认使用GET方式
     * @return  Array 服务返回的JSON数组
     */
    public static function QueryURL($url, $param = array(), $isPOST = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($isPOST) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        } else if (!empty($param)) {
            $xi = parse_url($url);
            $url .= empty($xi['query']) ? '?' : '&';
            $url .= http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if ($result == false) {
            throw new YBException(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * API调用方法
     *
     * @param   String api接口地址
     * @param   Array 请求参数数组
     * @param   Boolean 是否使用POST方式请求,默认使用GET方式
     * @param   Boolean 请求参数中是否需要传access_token
     * @return  Array 服务返回的JSON数组
     */
    public function request($url, $param = array(), $isPOST = false, $applyToken = true)
    {
        $url = self::YIBAN_OPEN_URL . $url;
        if ($applyToken) {
            $param['access_token'] = $this->_config['token'];
        }

        return self::QueryURL($url, $param, $isPOST);
    }

    /**
     * 获取配置参数
     *
     * @param String 配置名称
     */
    public function getConfig($configName)
    {
        return $this->_config[$configName];
    }

    /**
     * 轻应用接入
     *
     * @return YBAPI::IApp
     */
    public function getIApp()
    {

        if (!isset($this->_instance['iapp'])) {
//
            $this->_instance['iapp'] = new YBAPI_IApp($this->_config);
        }

        return $this->_instance['iapp'];
    }

}

class YBAPI_IApp
{

    const API_OAUTH_CODE = "oauth/authorize";

    private $appJsUrl = 'http://f.yiban.cn/';//

    /**
     * 构造函数
     *
     * 使用YBOpenApi里的config数组初始化
     *
     * @param   Array 配置（对应YBOpenApi里的config数组）
     */
    public function __construct($config)
    {

        foreach ($config as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * 对轻应用授权进行验证
     *
     * 对于轻应用通过页面跳转的方式，
     * 认证时从GET的参数verify_request串中解密出相关授权信息
     * 如已经授权，显示应用内容，
     * 若末授权，则跳转到授权服务去进行授权
     *
     * @return Array 授权信息数据
     */
    public function perform()
    {


        $code = $_GET['verify_request'];

        if (!isset($code) || empty($code)) {
//            throw new YBException(YBLANG::E_EXE_PERFORM);
        }
        $decInfo = $this->decrypts($code);
        if (!$decInfo) {
//            throw new YBException(YBLANG::E_DEC_STRING);
        }
        if (!is_array($decInfo) || !isset($decInfo['visit_oauth'])) {
//            throw new YBException(YBLANG::E_DEC_RESULT);
        }
        if (!$decInfo['visit_oauth']) {//未授权跳转
            header('location: ' . $this->forwardurl());
            return false;
        }
        return $decInfo;
    }

    //解密授权信息
    public function decrypts($code)
    {
        $encText = addslashes($code);
        $strText = pack("H*", $encText);
        $decText = (strlen($this->appid) == 16) ? mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->seckey, $strText, MCRYPT_MODE_CBC, $this->appid) : mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->seckey, $strText, MCRYPT_MODE_CBC, $this->appid);
        if (empty($decText)) {
            return false;
        }
        $decInfo = json_decode(trim($decText), true);
        return $decInfo;
    }

    /**
     * 生成授权认证地址
     *
     * 重定向到授权地址
     * 获取授权认证的CODE用于取得访问令牌
     *
     * @return    String 授权认证页面地址
     */
    private function forwardurl()
    {
        assert(!empty($this->appid), YBLANG::E_NO_APPID);
        assert(!empty($this->backurl), YBLANG::E_NO_CALLBACKURL);

        $query = http_build_query(array(
            'client_id' => $this->appid,
            'redirect_uri' => $this->backurl,
            'display' => 'html',
        ));
        return YBOpenApi::YIBAN_OPEN_URL . self::API_OAUTH_CODE . '?' . $query;
    }
}

class YBLANG
{
    const WEB_APP_TITLE = '开放平台-轻应用授权';
    const E_NO_APPID = '末设置AppID值！';
    const E_NO_APPSECRET = '末设置AppSecret值！';
    const E_NO_CALLBACKURL = '末设置回调地址！';
    const E_NO_ACCESSTOKEN = '末设置access_token值！';
    const E_EXE_PERFORM = 'perform()方法调用错误，请检查是否在轻应用入口页面进行调用！';
    const E_DEC_STRING = 'verify_request参数解密失败！';
    const E_DEC_RESULT = 'verify_request参数解密结果异常！';
    const EXIT_NOT_AUTHORIZED = '未通过授权，请先完成授权认证再测试功能接口！';
}



//    开发者验证
//public function valid_token()
//{
//    define('TOKEN', 'lishuo');
//    $yibanUrlObj = new Yiban_URL_VALIDATE();
//    $yibanUrlObj->valid();
//}
//class Yiban_URL_VALIDATE
//{
//    public function valid()
//    {
//        echo $this->checkSignature() ? $_GET["echostr"] : '';
//        exit;
//    }
//
//    private function checkSignature()
//    {
//        $tmpArr = array(TOKEN, $_GET['timestamp'], $_GET['nonce']);
//        sort($tmpArr, SORT_STRING);
//
//        return sha1(implode($tmpArr)) == $_GET['signature'];
//    }
//}

