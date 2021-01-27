<?php
namespace acme\facde;

/**
 * Class WxappModel
 * @method static checkStatus() 判断登录|发布状态
 * @method static getLoginQrcode() 获取登录二维码
 * @method static pushNewWxapp() 发布新版本小程序
 * @package acme\facde
 */
class WxappModel extends \think\Facade
{
    public static function getFacadeClass()
    {
        return \acme\WxappModel::class;
    }
}
