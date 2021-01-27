<?php
/**
 * Created by PhpStorm.
 * User: qd_008
 * Date: 2021/1/20
 * Time: 16:01
 */

namespace acme\controller;


use app\BaseController;

class CommonController extends BaseController
{

    /**
     * 变量注册&视图渲染
     * @param string $tpl
     * @param array $assign
     * @return object|\think\App
     */
	public function display($tpl='index',$assign=[])
	{
		return app('tpl',compact('tpl','assign'));
	}

	/**
	 * 成功信息
	 * @param string $msg
	 * @param array $data
	 * @return \think\response\Json
	 */
	public function success($msg='',$data=[])
	{
		return json(array_merge(['msg'=>$msg,'code'=>1],$data));
	}

	/**
	 * 错误信息
	 * @param string $msg
	 * @param int $code 错误状态码
	 * @return \think\response\Json
	 */
	public function error($msg='',$code=200)
	{
		return json(['msg'=>$msg,'code'=>0],$code);
	}
}
