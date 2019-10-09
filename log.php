<?php

/**
 * Created by ipsblab
 * Author: xiaojun.lan
 * Date Time: 2019/10/9 9:29
 * Description:
 */
class log
{

    private static $isDebug = true;
    public function __construct()
    {

    }

    /**
     * 写debug日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否强制写入
     */
    public static function debug($info,$write = false){
        $rootPath = '/usr/local/nginx/html/burst-explorer/';
//        if(log::$isDebug || $write){
            $time = date('Y-m-d H:i:s');
            file_put_contents($rootPath.'logs/exdebug.log',$time.": ".$info.PHP_EOL,FILE_APPEND);
//        }
    }

    /**
     * 写test日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否强制写入
     */
    public static function test($info,$write = false){
        $rootPath = '/usr/local/nginx/html/burst-explorer/';
//        if(log::$isDebug || $write){
        $time = date('Y-m-d H:i:s');
        file_put_contents($rootPath.'logs/test.log',$time.": ".$info.PHP_EOL,FILE_APPEND);
//        }
    }

}


