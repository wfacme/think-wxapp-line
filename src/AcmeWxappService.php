<?php
namespace acme;


use think\App;
use app\Request;
use think\facade\Config;
use think\facade\Route;
use think\Service;
use think\Template;

class AcmeWxappService extends Service
{
	public $bind = [
		"acme\contracts\HttpContract" => HttpService::class
	];

	public function boot()
	{
	    //模板处理
		$this->app->bind('tpl',function (App $app,Request $rst,$tpl=null,$assign=[]){
			$defaultPath = __DIR__.'/./template/';
			if(is_array($tpl)) {
				$assign = $tpl;
				$tpl = null;
			}
			$template = new Template([
				'layout_on'     =>  false,
				'view_path'		=>	config('acme.view_path',$defaultPath),
				'view_suffix'   =>	config('acme.view_suffix','html'),
			]);
			return $template->fetch($tpl?$tpl:'index',['acme'=>$assign]);
		});
	}

	/**
	 * 实现可自定义页面路径
	 */
	public function register()
	{
		$this->mergeConfig();
		$this->registerAcmeRoutes();
	}

	/**
	 * 注册组件路由
	 * 所有路由都是基于 \acme\controller\ 前缀
	 */
	public function registerAcmeRoutes()
	{
		Route::group('addons/wxapp',function (){
			require_once(__DIR__.'/config/route.php');
		})->prefix("\\acme\\controller\\")->middleware(config('acme.routeMiddleware'));
	}

	/**
	 * 合并配置文件
	 */
	public function mergeConfig()
	{
		$config = config('acme');
		Config::set(array_merge(
			require_once(__DIR__.'/config/acme.php'),
			$config
		),'acme');
	}
}
