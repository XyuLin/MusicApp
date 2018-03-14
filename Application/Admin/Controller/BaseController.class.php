<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017-12-18
 * Time: 16:10
 */

namespace Admin\Controller;


use Think\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!session('id')){
            $this->error('您没有权限操作！请先登录...',U('Login/login'));
        }
    }
}