<?php
class partner_controller extends desktop_controller {

	public function __construct($app) {
		parent::__construct($app);
        $this->user = kernel::single('desktop_user');
        $this->_setCaller("agent:".$this->user->user_data['name']);
	}

    private $_caller = null;
    private function _setCaller($name) {
        if(empty($name) || $this->_caller)  return false;
        $this->_caller = $name;
        ecae_set_caller($name);
    } // end function _setCaller

	// 检测权限
	public function checkPermission($permission,$return = false) {
		$flag = $this->user->has_permission($permission);
		if($return) return $flag;
		if(!$flag) die("Invalid Permission");
	}// end function checkPermission

    // array("ctl"=>"admin_site","act"=>"index","app"=>"partner")
    public function getView($mdl,$arrParam) {
        $filter = app::get('desktop')->model('filter');
        $ctl = $arrParam["app"]."_ctl_".$arrParam['ctl'];
        $objCtl = new $ctl($this->app);
        $arrView = array();
        if (method_exists($objCtl,"_views")) {
            $arrTemp = $objCtl->_views();
            foreach($arrTemp as $row) {
                $arrView[] = $row['filter'];
            }
        } else {
            $arrView[] = null;
        }
        $_filter = array(
            'mode'    => $mdl,
            'app'     => $arrParam["app"],
            'ctl'     => $arrParam["ctl"],
            'act'     => $arrParam["act"],
            'user_id' => $this->user->user_id,
        );
        $rows = $filter->getList('filter_query',$_filter,0,-1,'create_time asc');
        foreach($rows as $row) {
            $temp = parse_str($row['filter_query'],$arrT);
            $arrView[] = $arrT;
        }
        return $arrView;
    } // end function getView

} // end class 
