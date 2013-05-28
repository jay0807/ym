<?php
class partner_mdl_package extends partner_model{
    
    // 站点数量
    public function count($filter=null){
        return kernel::single("ecaeapi_package")->get_count($this->_filter($filter));
    } // end function count

    // 站点列表
    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        if($limit <= 0) {
             $arrResult = kernel::single("ecaeapi_package")->get_all_list($this->_filter($filter));
        } else {
             $arrResult = kernel::single("ecaeapi_package")->get_list($offset,$limit,$this->_filter($filter));
        }
        return $arrResult;
    } // end function getList 
    
    public function _filter($filter){
        return kernel::single("partner_filter_package")->filter($filter);
    } // end function _filter

    private $_package_data = array();
    public function dump($package_id) {
        if(!isset($this->_package_data[$package_id])) { 
            $this->_package_data[$package_id] = $this->_dump($package_id);
        }
        return $this->_package_data[$package_id];
    } // end function dump
    
    private function _dump($package_id) {
        return kernel::single("ecaeapi_package")->get_info($package_id);
    } // end function _dump 

    public function get_schema(){
        $schema = array (
            'columns' => array (
                'name' => array (
                    'type'          => 'varchar(20)',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('套餐名称'),
                    'searchtype'    => "has",
                    'filtertype'    => "has",
                    'filterdefault' => true,
                    'order'         => 5
                ),
                'package_id' => array (
                    'type'          => 'varchar(10)',
                    'label'         => app::get('partner')->_('套餐ID'),
                    'width'         => 120,
                    'required'      => true,    
                    'pkey'          => true,  
                    'searchtype'    => "has",
                    'filtertype'    => "has",
                    'filterdefault' => true,
                ),
                'price'=>array(
                    'type'    => 'number',
                    'label'   => app::get('partner')->_('套餐价格(元/月)'),
                    'orderby' => false,
                    'order'   => 6
                ),
                'network' => array(
                    'type'    => 'int(5)',
                    'orderby' => false,
                    'label'   => app::get('partner')->_('流量(T/月)'),
                    'order'   => 7
                ),
                'db_size' => array(
                    'type'    => 'int(5)',
                    'orderby' => false,
                    'label'   => app::get('partner')->_('数据库大小(M)'),
                    'order'   => 8
                ),
                'image_size' => array(
                    'type'    => 'int(5)',
                    'orderby' => false,
                    'label'   => app::get('partner')->_('图片存储大小(G)'),
                    'order'   => 9
                ),
                'code_size' => array(
                    'type'    => 'int(5)',
                    'orderby' => false,
                    'label'   => app::get('partner')->_('代码存储空间大小(M)'),
                    'order'   => 10
                ),
                'status'=>array(
                    'type' => array(
                        "up"   => app::get('partner')->_('已上架'),
                        "down" => app::get('partner')->_('已下架'),
                    ),
                    'label'         =>  app::get('partner')->_('套餐状态'),
                    'orderby'       => false,
                    'width'         => 60,
                    'order'         => 11,
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                ),
                'desc'=>array(
                    'type'    => 'text',
                    'label'   => app::get('partner')->_('套餐描述'),
                    'orderby' => false,
                    'width'   => 120,
                    'order'   => 12
                ),
            ),
            'idColumn' =>'package_id',
            'textColumn' =>'name',
            'in_list' => array (
                    'package_id',
                    'name',
                    'price',
                    'status',
                    'desc',
                    'network',
                    'db_size',
                    'image_size',
                    'code_size',
            ),
            'default_in_list' => array (
                    'name',
                    'price',
                    'status',
                    'desc',
                    'network',
                    'db_size',
                    'image_size',
                    'code_size'
            ),
        );

        return $schema;
    } // end function get_schema()

    public function modifier_status($row) {
        $arrStatus = array(
                        "up"   =>"<font color=green>已上架</font>",
                        "down" =>"<font color=red>已下架</font>"
        );
        return isset($arrStatus[$row])? $arrStatus[$row] : $row;
    } // end function modifier_status

} // end class     
