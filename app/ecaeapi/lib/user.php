<?php
class ecaeapi_user
{
    public $msg;
    private $instance = null;
    
    public function __construct($app) {
        $this->app = $app;
        $this->instance = ecaeapi_api::getInstance();
    }

    function add($uid,$pwd,$email)
    {
        try{
            return $this->instance->user_add($uid,$pwd,$email);
        }catch(Exception $e){
            $this->msg = '注册失败！帐号重复';
            return false;
        }
    }

    public function change_password($uid,$oldpwd,$pwd)
    {
        try{
            return $this->instance->user_change_password($uid,$oldpwd,$pwd);
        }catch(Exception $e){
            $this->msg = '注册失败！帐号重复';
            return false;
        }
    }
    
    public function is_valid($uid){
        try{
            return $this->instance->user_is_valid($uid);
        }catch(Exception $e){
            return false;
        }
    }

    public function is_exist($uid){
        try{
            return $this->instance->user_is_exist($uid);
        }catch(Exception $e){
            return false;
        }
    }

    public function gen_password($uid){
        try{
            return $this->instance->user_gen_password($uid);
        }catch(Exception $e){
            return false;
        }
    }

    public function check_password($uid, $pwd) 
    {
        try{
            return $this->instance->user_check_password($uid,$pwd);
        }catch(Exception $e){
            return false;
        }
    }//End Function

    public function set_attr($uid,$key,$val)
    {
        try{
            return $this->instance->user_set_attr($uid,$key,$val);
        }catch(Exception $e){
            return false;
        }
    }

    public function lists()
    {
        try{
            return $this->instance->user_list();
        }catch(Exception $e){
            return false;
        }
    }


    public function dump_attr($uid)
    {
        try{
            return $this->instance->user_dump_attr($uid);
        }catch(Exception $e) {
            return false;
        }
    }

    public function get_attr($uid,$key)
    {
        try{
            return $this->instance->user_get_attr($uid,$key);
        }catch(Exception $e) {
            return false;
        }
    }

// === Agent ===================================================================

    /**
     *
     *
     *
     **/
    public function getList($page,$limit = 20,$sort = 'descending') {
        try{
            return $this->getListAll();
            //return $this->instance->agent_list_users($page,$limit,$sort);
        } catch (Exception $e) {
            return false;
        }
    } // getList

    public function getListAll() {
        try{
            return $this->instance->agent_list_users();
        } catch (Exception $e) {
            return false;
        }
    } // getListAll

    public function setStatus($user_id,$active = true) {
        if($active) {
            return $this->active($user_id);
        } else {
            return $this->deactive($user_id);
        }
    }

    public function active($user_id) {
        try{
            return $this->instance->user_active($user_id);
        } catch (Exception $e) {
            return false;
        }
    } // active
    
    public function deactive($user_id) {
        try{
            return $this->instance->user_deactive($user_id);
        } catch (Exception $e) {
            return false;
        }
    } // active

    public function dump($user_id) {
        try{
            return $this->instance->user_get_info($user_id);
        } catch (Exception $e) {
            return false;
        }
    }// dump

    public function getSiteList($user_id) {
        try{
            return $this->instance->user_list_site($user_id);
        } catch (Exception $e) {
            return false;
        }
    } // getSiteList

    /**
     * 用户列表
     *
     * @param int    $offset // 偏移起始位置 从1开始
     * @param int    $limit  // 偏移量 
     * @param array  $where  // 条件 array(array('status','true'),array('email','xxx@shopex.cn')) 
     * @return array
     **/
    public function get_list($offset = 1,$limit = 20 ,$where = array(),$sort = null) {
        $offset = ($offset < 1)? 1  : intval($offset) + 1;
        $limit  = ($offset < 1)? 20 : intval($limit);
        if($sort) {
            return $this->instance->user_list($offset,$limit,$where,$sort);
        } else {
            return $this->instance->user_list($offset,$limit,$where);
        }
    } // end function get_list

    /**
     * 用户列表(所有)
     *
     * @param array  $where  // 条件 array(array('status','true'),array('email','xx@shopex.cn'))
     * @return array
     **/
    public function get_all_list($where = array()) {
        return $this->instance->user_list($where);
    } // end function get_all_list

    /**
     * 用户数量
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','xx@shopex.cn')) 
     * @return array
     **/
    public function get_count($where = array()) {
        return $this->instance->user_count($where);
    } // end function get_count

    /**
     * 用户站点创建数量
     *
     * @param string  $user_id 
     * @return array
     **/
    public function get_alternation($user_id) {
        return $this->instance->user_get_alternation($user_id);
    } // end function get_alternation

    /**
     * 设置用户站点创建数量
     *
     * @param string  $user_id      // 用户ID
     * @param int     $maxnum       // 晨大创建站点数 
     * @param int     $period       // 周期数
     * @param string  $period_type  // 周期类型 "day" | "month" | "year"
     * @return boolean
     **/
    public function set_alternation($user_id,$maxnum,$period,$period_type = "month") {
        $period_type = in_array($period_type,array("day","month","year","life"))? $period_type : "month";
        return $this->instance->user_set_alternation($user_id,intval($maxnum),intval($period),$period_type);
    } // end function set_alternation

    /**
     * 保存指定用户信息
     *
     * @param string  $user_id 
     * @param array   $arrData  // array(array(k,v),array(k1,v1)...)
     * @return array
     **/
    public function set_info($user_id,$arrData) {
        return $this->instance->user_set_info($user_id,$arrData);
    } // end function set_info

} // end class 
