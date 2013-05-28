<?php
class partner_mdl_domain extends partner_model{
    
    // 站点数量
    public function count($filter=null){
        return kernel::single("ecaeapi_domain")->get_count($this->_filter($filter));
    } // end function count

    // 站点列表
    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        $arrResult = array();
        if($limit <= 0) {
             $arrTemp = kernel::single("ecaeapi_domain")->get_all_list($this->_filter($filter));
        } else {
             $arrTemp = kernel::single("ecaeapi_domain")->get_list($offset,$limit,$this->_filter($filter));
        }
        foreach($arrTemp as $row) {
            $row['id']   = $row['site_id']."|".$row['domain'];
            $arrResult[] = $row;
        }
        return $arrResult;
    } // end function getList 
    
    public function _filter($filter){
        return kernel::single("partner_filter_domain")->filter($filter);
    } // end function _filter

    public function get_schema(){
        $schema = array (
            'columns' => array (
               'id' =>array (
                    'type'     => 'varchar(50)',
                    'orderby'  => false,
                    'label'    => app::get('partner')->_('ID'),
                ),
                'domain' =>array (
                    'type'          => 'varchar(50)',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('独立域名'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 5
                ),
                'site_id' => array(
                    'type'          => 'varchar(50)',
                    'orderby'       =>false,
                    'label'         => app::get('partner')->_('站点ID'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 11
                ),
                'status' => array(
                    'type'          => array (
                        true  => "已开启",
                        false => "已关闭",
                    ),
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('开启状态'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 9
                ),
                'verify' => array(
                    'type'          => array (
                        true  => "审核中",
                        false => "通过审核",
                    ),
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('审核状态'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 8
                ),
            ),
            'idColumn' =>'id',
            'in_list' => array (
                    'domain',
                    'status',
                    'verify',
                    'site_id',
            ),
            'default_in_list' => array (
                    'domain',
                    'status',
                    'verify',
                    'site_id'
            ),
        );
        return $schema;
    } // end function get_schema

    public function modifier_status($row) {
        $arrStatus = array(
            true     => "<font color=green>已开启</font>",
            false    => "<font color=red>已关闭</font>",
        );
        return $arrStatus[$row];
    } // end function modifier_status

    public function modifier_verify($row) {
        $arrStatus = array(
            true     => "<font color=green>通过审核</font>",
            false    => "<font color=red>审核中</font>",
        );
        return $arrStatus[$row];
    } // end function modifier_verify

} // end class     
