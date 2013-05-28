<?php
class partner_ctl_admin_package extends partner_controller {
    
    public function index() {
        $this->checkPermission("ecae_package");
        $actions = array();
        
        $this->finder('partner_mdl_package',array(
            'title'               => '套餐列表',
            'actions'             => $actions,
            'use_buildin_filter'  => true,
            'use_buildin_recycle' => false,
            //'use_buildin_export'=>true,
        ));
    } // end function index

}// end class 
