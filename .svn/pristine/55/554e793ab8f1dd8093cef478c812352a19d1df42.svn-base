<?php
class ecaeapi_domain {

    public function __construct($app) {
        $this->app = $app;
        $this->instance = ecaeapi_api::getInstance();
    }

    /**
     * 独立域名列表
     *
     * @param int    $offset // 偏移起始位置 从1开始
     * @param int    $limit  // 偏移量 
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_list($offset = 1,$limit = 20 ,$where = array()) {
        $offset = ($offset < 1)? 1  : intval($offset) + 1;
        $limit  = ($offset < 1)? 20 : intval($limit);
        return $this->instance->domain_list($offset,$limit,$where);
    } // end function get_list

    /**
     * 独立域名列表(所有)
     *
     * @param array  $where  // 条件 array(array('status','true') 根据 
     * @return array
     **/
    public function get_all_list($where = array()) {
        return $this->instance->domain_list($where);
    } // end function get_all_list

    /**
     * 独立域名数量
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_count($where = array()) {
        return $this->instance->domain_count($where);
    } // end function get_count

    // 绑定域名
    public function add($site_id,$domain) {
        return $this->instance->site_add_domain($site_id,$domain);
    }

    public function delete($site_id,$domain) {
        return $this->instance->site_delete_domain($site_id,$domain);
    }

    public function active($site_id,$domain) {
        return $this->instance->site_active_domain($site_id,$domain);
    }

    public function deactive($site_id,$domain) {
        return $this->instance->site_deactive_domain($site_id,$domain);
    }

} // end class
