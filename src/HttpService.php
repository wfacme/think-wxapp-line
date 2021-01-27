<?php
/**
 * Created by PhpStorm.
 * User: qd_008
 * Date: 2021/1/25
 * Time: 14:54
 */

namespace acme;

use Curl\Curl;
use acme\contracts\HttpContract;

class HttpService extends Curl implements HttpContract
{
	/**
	 * 获取返回数据
	 * @return false|object
	 */
	public function getBody()
	{
		return json_decode($this->response);
	}

	/**
	 * 获取请求状态码
	 * @return int|mixed
	 */
	public function getStatusCode()
	{
		return $this->getHttpStatusCode();
	}

}
