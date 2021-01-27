<?php

return [

	//本机用户
	'protPath' => 'C:/wxkf/.ide',

	//获取
	'pushMiddleware' => function($request,\Closure $next){
		$request->appid = "1221";
		$request->appid = "wx98bee1841bd4bbb7";
		return $next($request);
	},

	//路由中间件
	'routeMiddleware' => [],

    //小程序配置地址
    'wxappSettingUrl' => ''

];
