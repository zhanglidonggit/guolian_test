<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
    public function __construct(){
        parent::__construct();
	}
	
	/*
		获取上周积分增长最快的前十位用户 crontab
		运行方式：php bin/cron.php command/index/getLastWeekTopTen
	*/
	public function getLastWeekTopTen(){
		$this->load->model('user_integral_model');
		$data = $this->user_integral_model->getLastWeekTopTen();
		$msg = "上周积分增长Top10\n";
		if($data){
			foreach($data as $k=>$v){
				$msg .= ($k+1).". 用户ID:".$v["user_id"]." 积分增长:".$v["total"]."\n";
			}
		}
		$msg = trim($msg,"\n");
		if($msg){
			require_once APPPATH.'core/MY_Common.php';
			record_log('lastweektop',$msg);
		}
		exit;		
	}
}