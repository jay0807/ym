<?php
class partner_mdl_site extends partner_model{
    
    // 站点数量
    public function count($filter=null){
        return kernel::single("ecaeapi_site")->get_count($this->_filter($filter));
    } // end function count

    // 站点列表
    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        if($limit <= 0) {
             $arrTemp = kernel::single("ecaeapi_site")->get_all_list($this->_filter($filter));
        } else {
             $arrTemp = kernel::single("ecaeapi_site")->get_list($offset,$limit,$this->_filter($filter),$this->_sort($orderType));
        }
        $arrDomain = $this->getDomainList();
        $domain    = isset($arrDomain[0])? ".".$arrDomain[0]['domain'] : "";
        foreach($arrTemp as $key=>$row) {
            $row['domain'] = $row['domain'].$domain;
            $arrResult[] = $row;
        }
        return $arrResult;
    } // end function getList

    public function _sort($sort) {
        return kernel::single("partner_filter_site")->sort($sort);
    } // end function _sort

    public function _filter($filter){
        return kernel::single("partner_filter_site")->filter($filter);
    } // end function _sort

    public function _dump($site_id) {
        return kernel::single("ecaeapi_site")->get_info($site_id,2);
    } // end function _dump

    private $_data_site = array();
    public function dump($site_id) {
        if(!isset($this->_data_site[$site_id]))  {
            $this->_data_site[$site_id] = $this->_dump($site_id);
        }
        return $this->_data_site[$site_id];
    } // end function dump

    private $_domain = null;
    public function getDomainList() {
        if(empty($this->_domain)) {
            $this->_domain = kernel::single("ecaeapi_agent")->getDomainList();
        }
        return $this->_domain;
    } // end function getDomainList

    public function save($arrData) {
        if(!isset($arrData['site_id']) || empty($arrData['site_id'])) {
            return $this->_insert($arrData);
        } else {
            return $this->_update($arrData,$arrData['site_id']);
        }    
    } // end function save

    // 新开通一个站点
    private function _insert($arrData) {
        $objSite = kernel::single("ecaeapi_site");
        $user    = trim($arrData['user_id']);
        $name    = trim($arrData['name']);
        if(!$objSite->add($user,$name,trim($arrData['package']),trim($arrData['app'])) ) {
            return false;
        }
        $arrOption = array(
            array('title',     htmlspecialchars($arrData['title'])),
            array('period',    $arrData['period']),
            array('desc',      htmlspecialchars($arrData['intro'])),
            array('creattime', time()),
            array('starttime', intval($arrData['starttime'])),
            array('deadline',  strtotime("+".$arrData['period']." month",$arrData['starttime']))
        );
        return $objSite->set_info($name,$arrOption);
    } // end function _insert

    private function _update($arrData,$site_id) {
    } // end function _update
    
    public function get_schema(){
        $schema = array (
            'columns' => array (
               'site_id' =>array (
                    'type'     => 'varchar(10)',
                    'label'    => app::get('partner')->_('站点ID'),
                    'width'    => 120,
                    'required' => true,    
                    'pkey'     => true,
                    //'searchtype'=>"has",    
                ),
                'domain'=>array(
                    'type'          => 'varchar(30)',
                    'label'         => app::get('partner')->_('域名'),
                    'width'         => 200,
                    'orderby'       => false,
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 8
                 ),
                'name' => array(
                    'type'=>'varchar(10)',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('站点名称'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 5
                ),
                'application' => array(
                    'type'          => 'table:application',
                    //'type'          => "varchar(10)",
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('应用名称'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'breakpoint'    => 0,
                    'order'         => 6
                ),
                'package' => array(
                    'type'          => 'table:package',
                    //'type'          => "varchar(10)",
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('使用套餐'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'breakpoint'    => 0,
                    'order'         => 7
                ),
                'user' => array(
                    'type'          => 'table:user',
                    //'type'          => 'varchar(50)',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('创建者'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 9
                ),
                'period' => array(
                    'type'          => 'int',
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('开通周期'),
                    'order'         => 10
                ),
                'starttime' => array(
                    'type'          => 'time',
                    'orderby'       => true,
                    'label'         => app::get('partner')->_('开通时间'),
                    'filtertype'    => "time",
                    'filterdefault' => true,
                    'order'         => 11
                ),
                'deadline'=>array(
                    'type'          => 'time',
                    'orderby'       => true,
                    'label'         => app::get('partner')->_('到期时间'),
                    'filtertype'    => "time",
                    'filterdefault' => true,
                    'order'         => 12
                ),
                'creattime'=>array(
                    'type'          => 'time',
                    'orderby'       => true,
                    'label'         => app::get('partner')->_('创建时间'),
                    'filtertype'    => "time",
                    'filterdefault' => true,
                ),
                'type' => array(
                    'type'=>array(
                                "paas"=>"paas",
                                "saas"=>"saas",
                                "fork"=>"fork"
                    ),
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('站点类型'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'width'         => 80,
                    'order'         => 13
                ),
                'path' => array(
                    'type'    => 'varchar(10)',
                    'orderby' => false,
                    'label'   => app::get('partner')->_('发布路径'),
                ),
                'version' => array(
                    'type'          => 'varchar(10)',
                    'orderby'       => true,
                    'label'         => app::get('partner')->_('路径版本号'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'searchtype'    => "has",
                ),
                'status' => array(
                    'type' => array(
                        true  => "开启",
                        false => "关闭"
                    ),
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('访问状态'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'width'         => 80,
                    'order'         => 14
                ),
                'desc' => array(
                    'type'    => "text",
                    'orderby' => false,
                    'label'   => app::get('partner')->_('站点描述'),
                    'order'   => 15
                ),
            ),
            'idColumn' =>'site_id',
            'textColumn'=>'name',
            'in_list' => array (
                    'type',
                    'name',
                    'domain',
                    'package',
                    'deadline',
                    'creattime',
                    'starttime',
                    'period',
                    'status',
                    'path',
                    'version',
                    'user',
                    'application',
                    'package',
                    'desc'
            ),
            'default_in_list' => array (
                    'name',
                    'application',
                    'package',
                    'domain',
                    'user',
                    'period',
                    'starttime',
                    'deadline',
                    'status',
                    'type',
                    'desc',
            ),
        );
        return $schema;
    } // end function get_schema

    public function modifier_status($row) {
        $arrStatus = array(
            true  => "<font color=green>已开启</font>",
            false => "<font color=red>已关闭</font>"
        );
        return $arrStatus[$row];
    } // end function modifier_status

} // end class     
