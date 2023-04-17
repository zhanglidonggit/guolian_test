<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

if(!function_exists('record_log')) {
    function record_log($type = '', $msg = '', $path = '') {
        $loader = require APPPATH . "third_party/vendor/autoload.php";
        $loader->addPsr4('Monolog\\', APPPATH . 'third_party/Monolog');

        $output_format = "crontab执行时间：%datetime% \n%message% \n";
        $date_format = "Y-m-d H:i:s";
        $formatter = new LineFormatter($output_format, $date_format);

        $configs = sysLogConfig();

        if(!empty($path)) {
            $handler = new StreamHandler($path, Logger::DEBUG);
        } else {
            if(!isset($configs[$type])) {
                return false;
            }
            $handler = new StreamHandler($configs[$type]['logpath'] . $configs[$type]['filename']. "_". date('Ymd', time()) . ".log", Logger::DEBUG);
        }
        $handler->setFormatter($formatter);

        $logger = new Logger('monolog');
        $logger->pushHandler($handler);

        $logger->pushProcessor(new Monolog\Processor\WebProcessor($_SERVER));

        $logger->addRecord(Logger::INFO, $msg);
    }
}

if(!function_exists('sysLogConfig')) {
    function sysLogConfig() {
        return array(
            'test_monolog' => array(
                'logpath' => '/opt/logs/gl/',
                'filename' => 'lastweektop',
                'loglevel' => 100
            ),
        );
    }
}