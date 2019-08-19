<?php
/**
 * +----------------------------------------------------------------------
 * | 12xue [PhpStorm]
 * +----------------------------------------------------------------------
 *  .--,       .--,             | FILE: UserAgent.php
 * ( (  \.---./  ) )            | AUTHOR: byron
 *  '.__/o   o\__.'             | EMAIL: xiaobo.sun@qq.com
 *     {=  ^  =}                | QQ: 150093589
 *     /       \                | DATETIME: 2019-08-19 11:12
 *    //       \\               |
 *   //|   .   |\\              |
 *   "'\       /'"_.-~^`'-.     |
 *      \  _  /--'         `    |
 *    ___)( )(___               |-----------------------------------------
 *   (((__) (__)))              | 高山仰止,景行行止.虽不能至,心向往之。
 * +----------------------------------------------------------------------
 * | Copyright (c) 2019 http://www.zzstudio.net All rights reserved.
 * +----------------------------------------------------------------------
 */
namespace think;

use think\facade\Request;

class UserAgent
{
    /**
     * 分析信息
     * @param $string           UserAgent String
     * @param null $imageSize   Image Size(16 / 24)
     * @param null $imagePath   Image Path
     * @param null $imageExtension  Image Description
     * @return userAgent\UserAgent
     */
    public static function analyze($string = '', $imageSize = null, $imagePath = null, $imageExtension = null)
    {
        if (empty($string)) {
            $string = Request::header('User-Agent');
        }

        $class = new \think\userAgent\UserAgent();
        $imageSize === null || $class->imageSize = $imageSize;
        $imagePath === null || $class->imagePath = $imagePath;
        $imageExtension === null || $class->imageExtension = $imageExtension;
        $class->analyze($string);

        return $class;
    }
}