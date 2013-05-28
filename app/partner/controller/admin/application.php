<?php
class partner_ctl_admin_application extends partner_controller {

    public function index() {
        $this->checkPermission("ecae_application");
        $actions = array();
        if($this->checkPermission("ecae_application_add",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('添加应用'),
                    'href'=>'index.php?app=partner&ctl=admin_application&act=add',
                    'target'=>'dialog::{title:\''.app::get('platform')->_('添加应用').'\',width:720,height:420}'
            );
        }
        if($this->checkPermission("ecae_application_batch_status",true)) {
            $actions[] = array(
                    'label'=>app::get('partner')->_('上架'),
                    'submit'=>'index.php?app=partner&ctl=admin_application&act=status&p[0]=up',
                    'confirm'=> app::get('partner')->_('确定上架选中的应用?'),
            );
            $actions[] = array(
                    'label'=>app::get('partner')->_('下架'),
                    'submit'=>'index.php?app=partner&ctl=admin_application&act=status&p[0]=down',
                    'confirm'=>app::get('partner')->_('确定下架选中的应用?'),
            );
        }
        $this->finder('partner_mdl_application',array(
            'title'               => '应用列表',
            'actions'             => $actions,
            'use_buildin_filter'  => true,
            'use_buildin_recycle' => false,
            //'use_buildin_export'=>true,
        ));
    } // end function index

    private function _common($arrApp = array()) {
        $this->display("admin/application/edit.html");
    } // end function _common

    public function add() {
        $this->checkPermission("ecae_application_add");

        $this->pagedata['site'] = kernel::single("partner_site")->getAdminSiteList();
        $this->_common();
    } // end function add

    public function edit($app_id) {
        $this->checkPermission("ecae_application_edit");
        $objApp = $this->app->model("application");
        $arrData = $objApp->dump($app_id);
        $this->pagedata['data'] = $arrData;

        $this->_common($arrData);
    } // end function edit

    public function save() {
        $this->begin();
        if(empty($_POST['data'])) $this->end(false,app::get("partner")->_("没有提交数据")); 
        $this->end(kernel::single("partner_application")->save($_POST['data'],$strMsg),$strMsg);
    } // end function save

    // 上架&下架
    public function status($status) {
        $this->checkPermission("ecae_application_batch_status");
        $this->begin("index.php?app=partner&ctl=admin_application&act=index&finder_id=".$_GET['finder_id']);
        $objApp = kernel::single("partner_application");
        $this->end($objApp->setStatus($_POST,$status,$strMsg),$strMsg);
    } // end function status

}// end class 
