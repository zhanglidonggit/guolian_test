<?php
class User_integral_model extends CI_Model {
    public function __construct(){
        parent::__construct();
    }

    /**
     * 根据用户ID获取用户信息
     *
     * @param integer $user_id
     * @return void
     */
    public function getUserInfoById($user_id = 0){
        $user_id = intval($user_id);
        if(!$user_id){
            return [];
        }
        $this->db_read=$this->load->database('default',true);
        $res = $this->db_read->select("*")->from("gl_users")->where("id=",$user_id)->get()->row_array();
        return $res;
    }

    /*
        用户注册逻辑
    */
    public function register_action($username,$recommend_id){
        $this->db_write=$this->load->database('default',true);
        $username = trim($username);
        $recommend_id = intval($recommend_id);
        //事务
        $this->db_write->trans_begin();
        $recommend_info = [];
        if($recommend_id > 0){
            $recommend_info = $this->db_write->select("*")->from("gl_users")->where("id",$recommend_id)->get()->row_array();
        }
        $second_recommend_info = [];
        if($recommend_info){
            $second_recommend_info = $this->db_write->select("*")->from("gl_users")->where("id",$recommend_info["recomend_user_id"])->get()->row_array();
        }
        $registerInfo = array(
            'username' => $username,
            'integral'     => 0,
            'recomend_user_id'   => isset($recommend_info["id"]) ? $recommend_info["id"] : 0,
            'adddate'     => date("Y-m-d H:i:s",time()),
        );
        $res = $this->db_write->insert('gl_users',$registerInfo);
        if(!$res){
            $this->db_write->trans_rollback();
            return false;
        }
        $new_id = $this->db_write->insert_id();
        if(!$recommend_info){
            $this->db_write->trans_commit();
            return true;
        }
        //查询赠送积分配置信息
        $giveIntegralInfo = [];
        $giveIntegralInfo = $this->db_write->select("*")->from("gl_integral_config")->where("status",1)->order_by("adddate","desc")->limit(1)->get()->row_array();
        if(!$giveIntegralInfo){
            $giveIntegralInfo["first_integral"] = 10;
            $giveIntegralInfo["second_integral"] = 5;
        }
        // //直接推荐人
        $firstGiveInteral = [];
        $firstGiveInteral["change_type"] = 1;
        $firstGiveInteral["change_integral"] = $giveIntegralInfo["first_integral"];
        $firstGiveInteral["user_id"] = $recommend_info["id"];
        $firstGiveInteral["contri_user_id"] = $new_id;
        $firstGiveInteral["content"] = $recommend_info["id"]."邀请用户".$new_id."注册，作为直接推荐人获取赠送".$giveIntegralInfo["first_integral"]."积分";
        $firstGiveInteral["adddate"] = date("Y-m-d H:i:s",time());
        $firstGiveInteralRes = $this->db_write->insert('gl_user_change_integrals',$firstGiveInteral);
        if(!$firstGiveInteralRes){
            $this->db_write->trans_rollback();
            return false;
        }
        //改变直接推荐人积分
        $firstRecommendGiveInteral = [];
        $firstRecommendGiveInteral["integral"] = $recommend_info["integral"] + $giveIntegralInfo["first_integral"];
        $firstRecommendGiveInteral["update"] = date("Y-m-d H:i:s",time());
        $firstUpdateRecommendUser = $this->db_write->where('id', $recommend_info["id"])->update("gl_users", $firstRecommendGiveInteral);
        if(!$firstUpdateRecommendUser){
            $this->db_write->trans_rollback();
            return false;
        }
        if(!$second_recommend_info){
            $this->db_write->trans_commit();
            return true;
        }
        //二级推荐人
        $senondGiveInteral = [];
        $senondGiveInteral["change_type"] = 1;
        $senondGiveInteral["change_integral"] = $giveIntegralInfo["second_integral"];
        $senondGiveInteral["user_id"] = $second_recommend_info["id"];
        $senondGiveInteral["contri_user_id"] = $new_id;
        $senondGiveInteral["content"] = $recommend_info["id"]."邀请用户".$new_id."注册，".$second_recommend_info["id"]."作为二级推荐人获取赠送".$giveIntegralInfo["second_integral"]."积分";
        $senondGiveInteral["adddate"] = date("Y-m-d H:i:s",time());
        $senondGiveInteral = $this->db_write->insert('gl_user_change_integrals',$senondGiveInteral);
        if(!$senondGiveInteral){
            $this->db_write->trans_rollback();
            return false;
        }
        $secondRecommendGiveInteral = [];
        $secondRecommendGiveInteral["integral"] = $second_recommend_info["integral"] + $giveIntegralInfo["second_integral"];
        $secondRecommendGiveInteral["update"] = date("Y-m-d H:i:s",time());
        $secondUpdateRecommendUser = $this->db_write->where('id', $second_recommend_info["id"])->update("gl_users", $secondRecommendGiveInteral);
        if(!$secondUpdateRecommendUser){
            $this->db_write->trans_rollback();
            return false;
        }
        $this->db_write->trans_commit();
        return true;
    }

    /**
     * 获取无限级下级用户信息
     *
     * @param integer $id
     * @return void
     */
    public function getSubordinate($id = 0) {
        global $str;
        $this->db_read=$this->load->database('default',true);
        $sql = "select * from gl_users where recomend_user_id= $id";
        $result = $this->db_read->query($sql)->result_array();
		if($result){
            $str .= '<ul>';
            foreach($result as $k=>$v){
                $str .= "<li>" . $v['id'] . "--" . $v['username'] . "</li>";
                $this->getSubordinate($v['id']);
            }
			$str .= '</ul>';
		}
		return $str;
    } 

    /**
     * 获取上周积分增长最快的前10位用户
     *
     * @return void
     */
    public function getLastWeekTopTen(){
        $this->db_read=$this->load->database('default',true);
        $sql = "select sum(`change_integral`) as total,user_id from gl_user_change_integrals where change_type=1 and adddate between DATE_FORMAT( DATE_SUB( DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 1 WEEK), '%Y-%m-%d 00:00:00') and  DATE_FORMAT( SUBDATE(CURDATE(), WEEKDAY(CURDATE()) + 1), '%Y-%m-%d 23:59:59') group by `user_id` order by total desc limit 10";
        $res = $this->db_read->query($sql)->result_array();
        return $res;
    }

}
