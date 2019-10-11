<?php

/**
 * Created by blog.meiui.pub
 * Author: lamborg
 * Date Time: 2019/10/9 9:29
 * Description: 日志助手
 */
class log
{

    private static $isDebug = true;
    private static $rootPath = '';
    public function __construct()
    {
        log::$rootPath = dirname(__FILE__).'/';

    }

    /**
     * 写debug日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否写入
     */
    public static function debug($info, $write = true)
    {

        if (log::$isDebug && $write) {
            $time = date('Y-m-d H:i:s');
            file_put_contents(log::$rootPath . 'logs/debug.log', $time . ": " . $info . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * 写debug日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否写入
     *
     */
    public static function cron($info, $write = true)
    {

        if (log::$isDebug && $write) {
            $time = date('Y-m-d H:i:s');
            file_put_contents(log::$rootPath . 'logs/cron_debug.log', $time . ": " . $info . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * 写debug日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否写入
     */
    public static function cron_network_status($info, $write = true)
    {

        if (log::$isDebug && $write) {
            $time = date('Y-m-d H:i:s');
            file_put_contents(log::$rootPath . 'logs/cron_network_status_debug.log', $time . ": " . $info . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * 写debug日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否写入
     */
    public static function cron_peers($info, $write = true)
    {

        if (log::$isDebug && $write) {
            $time = date('Y-m-d H:i:s');
            file_put_contents(log::$rootPath . 'logs/cron_peers_debug.log', $time . ": " . $info . PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * 写test日志
     * @param $info string 需要记录的信息
     * @param bool|false $write 是否写入
     */
    public static function test($info, $write = true)
    {
        if (log::$isDebug && $write) {
            $time = date('Y-m-d H:i:s');
            file_put_contents(log::$rootPath . 'logs/test.log', $time . ": " . $info . PHP_EOL, FILE_APPEND);
        }
    }

}


