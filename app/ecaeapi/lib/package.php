<?php
class ecaeapi_package {

    public function __construct($app) {
        $this->app = $app;
        $this->instance = ecaeapi_api::getInstance();
    }

    /**
     * 套餐列表
     *
     * @param int    $offset // 偏移起始位置 从1开始
     * @param int    $limit  // 偏移量 
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_list($offset = 1,$limit = 20 ,$where = array()) {
        $offset = ($offset < 1)? 1  : intval($offset) + 1;
        $limit  = ($offset < 1)? 20 : intval($limit);
        return $this->instance->package_list($offset,$limit,$where);
    } // end function get_list

    /**
     * 套餐列表(所有)
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_all_list($where = array()) {
        return $this->instance->package_list($where);
    } // end function get_all_list

    /**
     * 套餐数量
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_count($where = array()) {
        return $this->instance->package_count($where);
    } // end function get_count

    /**
     * 套餐详情
     *
     * @param  int  $package_id
     * @return array
     **/
    public function get_info($package_id) {
        return $this->instance->package_get_info($package_id);
    } // end function get_info

} // end class
