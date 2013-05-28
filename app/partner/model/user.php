<?php
class partner_mdl_user extends partner_model{

    public function count($filter=null){
        return kernel::single("ecaeapi_user")->get_count($this->_filter($filter));
    } // end function count

    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        if($limit <= 0) {
            return kernel::single("ecaeapi_user")->get_all_list($this->_filter($filter));
        } else {
            return kernel::single("ecaeapi_user")->get_list($offset,$limit,$this->_filter($filter),$this->_sort($orderType));
        }
    } // end function getList

    public function _sort($sort){
        return kernel::single("partner_filter_user")->sort($sort); 
    } // end function _sort

    public function _filter($filter){
        return kernel::single("partner_filter_user")->filter($filter);
    } // end function _filter

    private $_data_user = array();
    public function dump($user_id) {
        if(!isset($this->_data_user[$user_id])) {
            $this->_data_user[$user_id] = $this->_dump($user_id); 
        }
        return $this->_data_user[$user_id];
    }// end function dump

    public function _dump($user_id) {        
        return kernel::single("ecaeapi_user")->dump($user_id);
    }// end function _dump

    // 获取指定用户的所有站点信息
    public function getSiteList($user_id) {
        $arrSite = kernel::single("ecaeapi_user")->getSiteList($user_id);
        $arrResult = array();
        if(empty($arrSite)) return $arrResult;
        foreach($arrSite as $row) {
            $arrResult[] = array(
                'domain'=>$row['site']["domain"],
                'type'=>$row['user']["type"],
                "path"=>$row['user']["path"],
            );
        }
        return $arrResult;
    } // end function getSiteList
 
    public function save($arrData) {
        $objUser = kernel::single("ecaeapi_user");
        if(!isset($arrData['id']) || empty($arrData['id'])) {
            if(!$objUser->add(
                trim($arrData['username']),
                trim($arrData['password']),
                trim($arrData['email'])
            )) return false;
            $user = trim($arrData['username']);
        } else {
            $user = $arrData['id'];
        }

        $arrOption = array(
            array('status',   ($arrData['status'])? "true" : "false"),
            array('tel',      $arrData['tel']),
            array('address',  htmlspecialchars($arrData['address'])),
            array('pcode',    $arrData['pcode']),
            array('username', htmlspecialchars($arrData['name']))
        );
        return $objUser->set_info($user,$arrOption);
    } // end function save
    
    public function get_schema(){
        $schema = array (
            'columns' => array (
               'user' =>array (
                    'type'          => 'varchar(10)',
                    'label'         => app::get('partner')->_('用户ID'),
                    'width'         => 120,
                    'required'      => true,    
                    'pkey'          => true,
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                ),
                'email' => array(
                    'type'          => 'varchar(255)',
                    'label'         => app::get('partner')->_('邮箱'),
                    'searchtype'    => "has",
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'orderby'       => false,
                    'order'         => 5,
                ),
                'creattime' => array(
                    'type'          => 'time',
                    'orderby'       => true,
                    'label'         => app::get('partner')->_('注册时间'),
                    'filtertype'    => "time",
                    'filterdefault' => true,
                    'order'         => 6,
                ),
                'sitenum' => array(
                    'type'    => 'int(3)',
                    'label'   => app::get('partner')->_('站点数量'),
                    'width'   => 90,
                    'orderby' => true,
                    'order'   => 7,
                ),
                'username' => array(
                    'type'    => 'varchar(20)',
                    'label'   => app::get('partner')->_('姓名'),
                    'orderby' => false,
                    'order'   => 8,
                ),
                'tel' => array(
                    'type'    => 'varchar(20)',
                    'label'   => app::get('partner')->_('联系电话'),
                    'width'   => 120,
                    'orderby' => false,
                    'order'   => 9 
                ),
                'pcode' => array(
                    'type'    => 'varchar(20)',
                    'label'   => app::get('partner')->_('邮编'),
                    'width'   => 80,
                    'orderby' => false,
                    'order'   => 10 
                ),
                'addr' => array(
                    'type'    => 'varchar(255)',
                    'label'   => app::get('partner')->_('联系地址'),
                    'width'   => 200,
                    'orderby' => false,
                    'order'   => 11
                ),
                'status'=>array(
                    'type' => array(
                        true  => "已启用",
                        false => "已禁用"
                    ),
                    'orderby'       => false,
                    'label'         => app::get('partner')->_('帐号状态'),
                    'filtertype'    => "custom",
                    'filterdefault' => true,
                    'order'         => 12
                ),

            ),
            'idColumn'   => 'user',
            'textColumn' => 'user',
            'in_list' => array (
                    'user',
                    'email',
                    'tel',
                    'addr',
                    'sitenum',
                    'status',
                    'creattime',
                    'username',
                    'pcode'
            ),
            'default_in_list' => array (
                    'email',
                    'tel',
                    'sitenum',
                    'status',
                    'creattime',    
                    'addr',
                    'sitenum',
                    'username',
                    'pcode'
            ),
        );
        return $schema;
    } // end function get_schema

    public function modifier_status($row) {
        $arrStatus = array(
            true  => "<font color=green>已启用</font>",
            false => "<font color=red>已禁用</font>"
        );
        return $arrStatus[$row];
    } // end function modifier_status

    public function modifier_csitenum($row) {    
        return ($row)? $row : '-';
    } // end function modifier_csitenum
    
} // end class     
