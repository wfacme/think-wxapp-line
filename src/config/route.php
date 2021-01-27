<?php

use think\facade\Route;

Route::get("push/index",'PushController@index');
Route::get("push/status",'PushController@status');
Route::post("push/upload",'PushController@upload')->middleware(
	config('acme.pushMiddleware',function ($request,Closure $next){
		return $next($request);
	})
);
