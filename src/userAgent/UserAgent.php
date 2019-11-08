<?php
/**
 * +----------------------------------------------------------------------
 * | ZzStudio [UserAgent]
 * +----------------------------------------------------------------------
 *  .--,       .--,             | FILE: UserAgent.php
 * ( (  \.---./  ) )            | AUTHOR: byron
 *  '.__/o   o\__.'             | EMAIL: xiaobo.sun@qq.com
 *     {=  ^  =}                | QQ: 150093589
 *     /       \                | DATETIME: 2019-08-19 11:21
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

namespace think\userAgent;

class UserAgent
{
    private $_imagePath = "";
    private $_imageSize = 16;
    private $_imageExtension = ".png";

    private $_data = [];

    public function __construct()
    {
        $this->_imagePath = dirname(dirname(dirname(__FILE__)));
    }

    public function __get($param)
    {
        $privateParam = '_' . $param;
        switch ($param) {
            case 'imagePath':
                return $this->_imagePath . '/assets/img/' . $this->_imageSize . '/';
                break;
            default:
                if (isset($this->$privateParam)) {
                    return $this->$privateParam;
                } elseif (isset($this->_data[$param])) {
                    return $this->_data[$param];
                }
                break;
        }

        return null;
    }

    public function __set($name, $value)
    {
        $trueName = '_' . $name;
        if (isset($this->$trueName)) {
            $this->$trueName = $value;
        }
    }

    private function _makeImage($dir, $code)
    {
        // 图片位置
        $img_file = $this->imagePath . $dir . '/' . $code . $this->imageExtension;

        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码
                switch ($img_info[2]) {           //判读图片类型
                    case 1: $img_type = "gif";
                        break;
                    case 2: $img_type = "jpg";
                        break;
                    case 3: $img_type = "png";
                        break;
                }

                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

            }
            fclose($fp);
        }

        return $img_base64;
    }

    private function _makePlatform()
    {

        $this->_data['platform'] = &$this->_data['device'];
        if ($this->_data['device']['title'] != '') {
            $this->_data['platform'] = &$this->_data['device'];
        } elseif ($this->_data['os']['title'] != '') {
            $this->_data['platform'] = &$this->_data['os'];
        } else {
            $this->_data['platform'] = [
                "link" => "#",
                "title" => "Unknown",
                "code" => "null",
                "dir" => "browser",
                "type" => "os",
                "image" => $this->_makeImage('browser', 'null'),
            ];
        }

    }

    public function analyze($string)
    {
        $this->_data['useragent'] = $string;
        $classList = array("device", "os", "browser");
        foreach ($classList as $value) {
            $class = "\\think\\userAgent\\detect\\" . ucfirst($value);
            // Not support in PHP 5.2
            //$this->_data[$value] = $class::analyze($string);
            $this->_data[$value] = call_user_func($class . '::analyze', $string);
            $this->_data[$value]['image'] = $this->_makeImage($value, $this->_data[$value]['code']);
        }

        // platform
        $this->_makePlatform();
    }
}