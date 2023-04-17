<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	/**
	 * 用户注册页面
	 *
	 * @return void
	 */
	public function register(){
		$this->load->view('register');
	}

	/**
	 * 用户注册方法
	 *
	 * @return void
	 */
	public function register_action(){
		$this->load->model('user_integral_model');
		$username = $this->input->get('username');
		$recommend_id = $this->input->get('recommend_id');
		$res = $this->user_integral_model->register_action($username,$recommend_id);
		if($res){
			echo "<script> alert('注册成功'); location.href='/welcome/index';</script>";
		} else {
			echo "<script> alert('注册失败'); location.href='/welcome/index';</script>";
		}
	}

	/**
	 * 批量注册页面
	 *
	 * @return void
	 */
	public function register_batch(){
		$this->load->view('register_batch');
	}

	/**
	 * 检测文件是否是excel文件
	 *
	 * @return void
	 */
	public function detectUploadFileMIME($file) {    
        $flag = 0;
        $file_array = explode ( ".", $file ["name"] );
        $file_extension = strtolower ( array_pop ( $file_array ) );
        switch ($file_extension) {
            case "xls" :        
                $fh = fopen ( $file ["tmp_name"], "rb" );
                $bin = fread ( $fh, 8 );
                fclose ( $fh );
                $strinfo = @unpack ( "C8chars", $bin );
                $typecode = "";
                foreach ( $strinfo as $num ) {
                    $typecode .= dechex ( $num );
                }
                if ($typecode == "d0cf11e0a1b11ae1") {
                    $flag = 1;
                }
            break;
            case "xlsx" :
                $fh = fopen ( $file ["tmp_name"], "rb" );
                $bin = fread ( $fh, 4 );
                fclose ( $fh );
                $strinfo = @unpack ( "C4chars", $bin );
                $typecode = "";
                foreach ( $strinfo as $num ) {
                    $typecode .= dechex ( $num );
                }
                if ($typecode == "504b34") {
                    $flag = 1;
                }
                break;
        }
        return $flag;
    }

	/*
		批量注册方法
	*/
	public function register_batch_action(){
		if(!isset($_FILES['excel_file'])){
			echo "<script> alert('请上传excel文件!'); location.href='/welcome/register_batch';</script>";
			exit;
		}
		if($_FILES['excel_file']['error']){
			$error = "";
			switch($_FILES['excel_file']['error']){
				case 1:
					$error = "文件大小超出了服务器的空间大小";
				break;
				case 2:
					$error = "文件大小超出了服务器的空间大小";
				break;
				case 3:
					$error = "文件仅部分被上传";
				break;
				case 4:
					$error = "没有找到要上传的文件";
				break;
				case 5:
					$error = "服务器临时文件缺失";
				break;
				case 6:
					$error = "文件写入到临时文件夹出错";
				break;
				default:
					$error = "未知错误";
			}
			if($error){
				echo "<script> alert('".$error."'); location.href='/welcome/register_batch';</script>";
				exit;
			}
		}
		$info = pathinfo($_FILES['excel_file']['name']);
		$file = $_FILES['excel_file'];
		$res = $this->detectUploadFileMIME($file);
		if(!$res){
			echo "<script> alert('请上传excel文件!'); location.href='/welcome/register_batch';</script>";
			exit;
		}
		$tmp_name = $info['filename'];
		$new_name = $tmp_name . '-' . time() .'.'. $info['extension'];
		$root_path = dirname(BASEPATH);
		$new_dir = $root_path . '/data/excel/';
		if (!file_exists($new_dir)) {
			mkdir($new_dir, 0777, true);
		}
		$new_path = $new_dir . $new_name;
		$new_path = str_replace('\\', DIRECTORY_SEPARATOR, $new_path);
		$new_path = str_replace('/', DIRECTORY_SEPARATOR, $new_path);
		$move_res = move_uploaded_file($_FILES['excel_file']['tmp_name'], $new_path);
		if (!$move_res) {
			echo "<script> alert('上传失败!'); location.href='/welcome/register_batch';</script>";
			exit;
		}
		$this->load->library('PHPExcel.php');
		$this->load->library('PHPExcel/IOFactory.php');
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();
		if($info['extension'] =='xlsx' ){
			$objReader = IOFactory::createReader('excel2007');
		}else{
			$objReader = IOFactory::createReader('Excel5');	
		}
		$objPHPExcel = $objReader->load($new_path);
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$excel_data = array();
		for ($col='A';$col<=$highestColumn;$col++) {
			for ($row=2;$row<=$highestRow;$row++) {
				$excel_data[$row-2][]=$sheet->getCell($col.$row)->getValue();
			}	
		}
		if($excel_data){
			$this->load->model('user_integral_model');
			foreach($excel_data as $k => $v){
				$username = $v[0];
				$recommend_id = $v[1];
				$this->user_integral_model->register_action($username,$recommend_id);
			}
		}
		echo "<script> alert('上传成功!'); location.href='/welcome/register_batch';</script>";
		exit;
	}

	/*
		获取无限级用户信息列表
	*/
	public function getSubordinateByUserId(){
		$user_id = intval($this->input->get('user_id'));
		$data["user_id"] = $user_id;
		$data["res"] = "";
		$data["user_info"] = [];
		$data["info"] = "";
		if($user_id){
			$this->load->model('user_integral_model');
			$user_info = $this->user_integral_model->getUserInfoById($user_id);
			$data["user_info"] = $user_info;
			$data["info"] = $this->user_integral_model->getSubordinate($user_id);
		}
		$this->load->view('subordinate',$data);
	}
}
