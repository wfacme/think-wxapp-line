<?php

namespace acme\contracts;

interface HttpContract
{

	/**
	 * 获取返回结果
	 * @return mixed
	 */
	function getBody();

	/**
	 * 获取当前请求状态
	 * @return mixed
	 */
	function getStatusCode();


	/**
	 * post请求
	 * @param $url string 地址
	 * @param array $params 参数
	 * @return mixed
	 */
	function post($url, $params = array());

	/**
	 * get请求
	 * @param $url string 地址
	 * @param array $params 参数
	 * @return mixed
	 */
	function get($url, $params = array());
}
