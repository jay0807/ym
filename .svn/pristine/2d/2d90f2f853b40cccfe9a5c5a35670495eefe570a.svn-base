<?php
class partner_mdl_application extends partner_model{

    public function count($filter=null){
        return kernel::single("ecaeapi_application")->get_count($this->_filter($filter));
    } // end function count

    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        if($limit <= 0) {
            $arrData =  kernel::single("ecaeapi_application")->get_all_list($this->_filter($filter));
            return $arrData;
        } else {
            return kernel::single("ecaeapi_application")->get_list($offset,$limit,$this->_filter($filter));
        }
    } // end function getList

    private $_app_data = array();
    public function dump($app_id) {
        if(!isset($this->_app_data[$app_id])) { 
            $this->_app_data[$app_id] = $this->_dump($app_id);
        }
        return $this->_app_data[$app_id];
    } // end function dump
    
    private function _dump($app_id) {
        return kernel::single("ecaeapi_application")->get_info($app_id,2);
    } // end function _dump
    
    public function _filter($filter){
        return kernel::single("partner_filter_application")->filter($filter);
    } // end function filter
    
    public function get_schema(){
        $schema = array (
            'columns' => array (
                 'name' => array(
                    'type'          => 'varchar(20)',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('应用名称'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 5
                ),
                'id' => array (
                    'type'       => 'varchar(10)',
                    'label'      => app::get('partner')->_('应用ID'),
                    'width'      => 120,
                    'required'   => true,    
                    'pkey'       => true,  
                    'orderby'    => false,
                    'searchtype' => "has",
                ),
                'price'=>array(
                    'type' => 'number',
                    'label'   => app::get('partner')->_('应用价格'),
                    'orderby' => false,
                    'width'   => 60,
                    'order'   => 7
                ),
                'type'=>array(
                    'type' => array(
                        "paas" => "paas",
                        "saas" => "saas",
                    ),
                    'label'         => app::get('partner')->_('应用类型'),
                    'orderby'       => false,
                    'width'         => 60,
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 8
                ),
                'site' => array(
                    'type'          => 'table:site',
                    'orderby'       => false,
                    'label'         => app::get('platform')->_('源站点名称'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 9
                ),
                'path' => array(
                    'type'    => 'varchar(50)',
                    'label'   => app::get('partner')->_('发布路径'),
                    'orderby' => false,
                    'width'   => 120,
                    'order'   => 10
                ),
                'version' => array(
                    'type'    => 'int',
                    'label'   => app::get('partner')->_('路径版本号'),
                    'orderby' =>false,
                    'width'   => 100,
                    'order'   => 11
                ),
                'status' => array(
                    'type' => array(
                        "up"   => "上架",
                        "down" => "下架",
                    ),
                    'label'         => app::get('partner')->_('应用状态'),
                    'orderby'       => false,
                    'width'         => 60,
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 12
                ),
                'desc' => array(
                    'type'    => 'text',
                    'label'   => app::get('partner')->_('应用描述'),
                    'orderby' => false,
                    'width'   => 100,
                    'order'   => 13
                 )
            ),
            'idColumn' =>'id',
            'textColumn' =>'name',
            'in_list' => array (
                    'id',
                    'name',
                    'site',
                    'type',
                    'status',
                    'price',
                    'path',
                    'version',
                    'desc'
            ),
            'default_in_list' => array (
                    'name',
                    'site',
                    'type',
                    'status',
                    'price',
                    'path',
                    'version',
                    'desc'
            ),
        );
        return $schema;
    } // end function get_schema

    public function modifier_status($row) {
        $arrStatus = array(
            "up"   => "<font color=green>已上架</font>",
            "down" => "<font color=red>已下架</font>"
        );
        return $arrStatus[$row];
    } // end function modifier_status

} // end class     
