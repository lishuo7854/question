<?php
/**
 * Created by PhpStorm.
 * User: 78548_000
 * Date: 2018/4/8
 * Time: 10:42
 */

namespace app\demo\controller;
use think\Cache;
use  app\demo\model\Difficult;
use  app\demo\model\Easy;
use  app\demo\model\Question;
use  app\demo\model\Middle;
use app\demo\model\Score;
use  app\demo\model\User;
use \think\View;
use think\Request;
use think\Db;

class Index extends \think\Controller
{
    public function save_score()
    {
//        存分数，返回等级
        $user_id = input('param.yb_userid');
        $user_score = input('param.score');
        $user_level = '皇帝';
        $user_table_id = Db::table('user')->where('yb_userid',$user_id)->select()[0]['id'];
        Db::table('score')->data(['user_id'=>$user_table_id,'score'=>$user_score,'level'=>$user_level])->insert();
        return  $user_level;
     }
//    返回用户信息排名以及院系排名


    public function index(){
        return
            $this->fetch('index');
    }

    public function question(){
        return  $this->fetch('question');
    }

    public function results(){
        return  $this->fetch('result');
    }



}


//public function index(){
//
//}
//
//public function question(){
//
//}
//
//public function result(){
//
//}