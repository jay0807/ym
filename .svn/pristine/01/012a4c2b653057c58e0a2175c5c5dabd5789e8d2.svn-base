<?php
class ecaeapi_application {
    private $instance = null;
    
    public function __construct($app) {
        $this->app = $app;
        $this->instance = ecaeapi_api::getInstance();
    }

    // 添加一个应用
    // 应用名称,源站点,svn路径,svn版本,应用类型,价格(给下级代理商的),开通资源 
    function add($app_name,$site_id,$path = "/trunk",$rev = 1,$scm_type = "paas",$price = 0,$parts = array()) {
        return $this->instance->goods_apps_add($app_name,$site_id,$path,intval($rev),$scm_type,intval($price),$parts);
    }// end function add 

    // 设置应用状态
    public function set_status($app_id,$status = "up") {
        if("up" == $status) {
            return $this->active($app_id);
        } else {
            return $this->deactive($app_id);
        }
    } // end function set_status

    // 激活应用
    public function active($app_id) {
        return $this->instance->goods_apps_up($app_id);
    } // end function active

    // 冻结应用    
    public function deactive($app_id) {
        return $this->instance->goods_apps_down($app_id);
    } // end function deactive

    // 设置应用价格    
    public function set_price($app_id,$price) {
        return $this->instance->goods_apps_set_price($app_id,intval($price));
    } // end function set_price

    // 设置应用简介(主要用于下级代理商查看)    
    public function set_intro($app_id,$intro = "") {
        return $this->instance->goods_apps_set_introduction($app_id,$intro);
    } // end function set_intro

    // 获取应用详情
    public function get_info($app_id,$v = 1) {
        if(1 == $v) {
            return $this->instance->goods_apps_get_info($app_id);
        } else {
            return $this->instance->app_get_info($app_id);
        }
    } // end function get_info
    
    // 设置应用信息
    public function set_info($app_id,$arrOptions,$v = 1) {
        if(1 == $v) {
            return $this->instance->goods_apps_set_info($app_id,$arrOptions);
        } else {
            return $this->instance->app_set_info($app_id,$arrOptions);
        }
    } // end function set_info

    
    // 获取应用所有扩展信息
    public function dump_attr($app_id) {
        return $this->instance->goods_apps_dump_attr($app_id);
    } // end function dump_attr

    // 获取应用指定的扩展信息
    public function get_attr($app_id,$key) {
        return $this->instance->goods_apps_get_attr($app_id,$key);
    } // end function get_attr
    
    // 设置应用指定的扩展信息
    public function set_attr($app_id,$key,$value) {
        return $this->instance->goods_apps_set_attr($app_id,$key,$value);
    } // end function set_attr

    /**
     * 应用列表
     *
     * @param int    $offset // 偏移起始位置 从1开始
     * @param int    $limit  // 偏移量 
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_list($offset = 1,$limit = 20 ,$where = array()) {
        $offset = ($offset < 1)? 1  : intval($offset) + 1;
        $limit  = ($offset < 1)? 20 : intval($limit);
        return $this->instance->app_list($offset,$limit,$where);
    } // end function get_list

    /**
     * 应用列表(所有)
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_all_list($where = array()) {
        return $this->instance->app_list($where);
    } // end function get_all_list

    /**
     * 应用数量
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_count($where = array()) {
        return $this->instance->app_count($where);
    } // end function get_count

} // end class
