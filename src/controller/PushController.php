<?php
/**
 * Created by PhpStorm.
 * User: qd_008
 * Date: 2021/1/20
 * Time: 10:41
 */

namespace acme\controller;

use acme\exceptions\AcmeLogicException;
use acme\facde\WxappModel;
use think\Exception;

class PushController extends CommonController
{

	public function index()
	{
        $qrcode = WxappModel::getLoginQrcode();
		return parent::display(compact('qrcode'));
	}

	public function status()
	{
		try{
			WxappModel::checkStatus();
			return $this->success("ok");
		}catch (AcmeLogicException $e){
			return $this->error($e->getMessage(),$e->getCode());
		}catch (Exception $e){
			 dd($e->getMessage());
		}
	}

	public function upload()
	{
		$rst = $this->request;
		try{
			$appid = $rst->appid;
		    $result = WxappModel::pushNewWxapp($rst->post(),$appid);
			return $this->success("打包成功",compact('result'));
        }catch (AcmeLogicException $e){
			return $this->error($e->getMessage(),$e->getCode());
        }catch (Exception $e){
		    dd($e->getMessage());
        }
	}

}
