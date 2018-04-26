<?php
/**
 * Created by PhpStorm.
 * User: 78548_000
 * Date: 2018/4/8
 * Time: 11:32
 */

namespace app\demo\model;
use  \think\Model;

class User extends Model
{
    public function score_data()
    {
        return $this->hasOne('Score');
    }
}