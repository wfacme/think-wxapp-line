<?php
namespace acme;

use acme\contracts\HttpContract;
use acme\exceptions\AcmeLogicException;

class WxappModel
{
    /**
     * 请求类
     * @var HttpContract
     */
    private $http;

	/**
	 * 端口号
	 * @var bool
	 */
    static $port = false;

    public function __construct(HttpContract $client)
    {
		$this->http = $client;
    }

	/**
	 * 获取登录二维码
	 * @return bool|mixed
	 * @throws AcmeLogicException
	 */
	public function getLoginQrcode()
	{
		//判断结果文件存在的话删除
		$this->checkResultExists();
		//获取登录二维码
        $this->http->get($this->mergeRequestUrl([
			'qr-format' 	=> 'base64',
			'result-output' => $this->getResultPath(),
		]));
        $result = $this->http->getBody();
        $code = $this->http->getStatusCode();
		return $code==200 ? $result->qrcode : false;
	}

	public function checkStatus()
	{
		$result = $this->checkResultExists();
		if($result!==false){
			if(isset($result['status'])){
				if($result['status']=="FAIL"){
					throw new AcmeLogicException($result['error'],400);
				}
			}
		}else{
			throw new AcmeLogicException("请求失败");
		}
	}

	/**
	 * 发布新版本小程序
	 * @param array $params
	 * @param null $appid
	 * @return mixed
	 * @throws AcmeLogicException
	 */
    public function pushNewWxapp($params=[],$appid=null)
    {
		$version = $params['version'];
        if(empty($version)) throw new AcmeLogicException("请填写小程序发布版本号");
        $resultPath = $this->getResultPath();
		//替换文件
		$templatePath = __DIR__ .'/../wxappTemplates/';
		$templateWxappPath = __DIR__ .'/../wxappTemplates/wxapp/';
		if(is_dir($templatePath.'/tpl')){
			$dir = $templatePath.'tpl/';
			foreach (scandir($dir) as $fileName){
				$filePath = $dir.$fileName;
				if(file_exists($filePath)&&is_file($filePath)){
					$templateString = file_get_contents($filePath);
					file_put_contents(
						$templateWxappPath.$fileName,
						str_replace(['{{APPID}}'],[$appid],$templateString)
					);
				}
			}
		}
		$this->http->get($this->mergeRequestUrl([
			'desc'		  => $params['remark'],
			'project'	  => $templateWxappPath,
			'version'	  => $version,
			//'info-output' => $resultPath,
		],'upload'));
		$result = $this->http->getBody();
		if($this->http->error){
			throw new AcmeLogicException($this->http->message);
		}
//		return json_decode($result);
		return '';
	}

    /**
     * 扫码结果文件
     * @return string
     */
    public function getResultPath()
    {
        return public_path().'wxappOut.lock';
    }

	/**
	 * 验证结果文件是否存在 Y:返回数据
	 * @return array|mixed
	 */
    private function checkResultExists(){
		$result = false;
		$path = $this->getResultPath();
		if(file_exists($path)){
			$result = json_decode( file_get_contents( $path ),true );
			@unlink($path);
		}
		return $result;
	}

	/**
	 * 整合请求地址
	 * @param array $query 请求参数
	 * @param string $type 请求方法
	 * @return string
	 * @throws AcmeLogicException
	 */
    private function mergeRequestUrl($query=[],$type='login'){
    	if(is_string($query)){
    		$type = $query;
			$query = [];
		}
        $domain = "http://127.0.0.1:{$this->getPort()}/v2/".$type;
        return $domain . (empty($query)?'':'?'.http_build_query($query));
    }


	/**
	 * 获取web开发者工具端口号
	 * @return bool|false|string
	 * @throws AcmeLogicException
	 */
    private function getPort(){
        $prot = self::$port;
        if(empty($prot)){
			$file_path = config('acme.protPath');
			if(file_exists($file_path)){
				$prot = file_get_contents($file_path);
			}
		}
        if(empty($prot)) throw new AcmeLogicException("请重新配置端口号");
        return $prot;
    }
}
