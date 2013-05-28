<?php
class partner_ctl_admin_user extends partner_controller {
    public function index() {
        $this->checkPermission("ecae_user");
        $actions = array();
        if($this->checkPermission("ecae_user_add",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('添加会员'),
                    'href'=>'index.php?app=partner&ctl=admin_user&act=add',
                    'target'=>'dialog::{title:\''.app::get('partner')->_('添加会员').'\',width:600,height:400}'
            );
        }
        if($this->checkPermission("ecae_user_batch_status",true)) {
            $actions[] = array(
                    'label'=>app::get('partner')->_('启用帐号'),
                    'submit'=>'index.php?app=partner&ctl=admin_user&act=status&p[0]=1',
                    'confirm'=>app::get('partner')->_("确定启用选中用户?"),
            );
            $actions[] = array(
                    'label'=>app::get('partner')->_('禁用帐号'),
                    'submit'=>'index.php?app=partner&ctl=admin_user&act=status&p[0]=0',
                    'confirm'=>app::get('partner')->_("确定停用选中用户?"),
            );
        }
        if($this->checkPermission("ecae_user_site_create",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('设置可创建站点数'),
                    'submit'=>'index.php?app=partner&ctl=admin_user&act=createnum',
                    'target'=>'dialog::{title:\''.app::get('partner')->_('设置可创建站点数').'\',width:360,height:160}'
            );
        }
        $this->finder('partner_mdl_user',array(
            'title'=>'会员列表',
            'actions'=>$actions,
            'use_buildin_filter'=>true,
            'use_buildin_recycle'=>false,
            //'use_buildin_export'=>true,
        ));
    }

    public function add() {
        $this->checkPermission("ecae_user_add");
        $this->display('admin/user/edit.html');
    } // end function add

    public function edit($user_id) {
        $this->checkPermission("ecae_user_edit");
        $objUser = $this->app->model("user");
        $arrData = $objUser->dump($user_id);

        $this->pagedata['data'] = $arrData;
        $this->pagedata['id'] = $user_id;

        $this->display('admin/user/edit.html');
    } // end function edit

    public function save() {
        $this->begin("index.php?app=partner&ctl=admin_user&act=index&finder_id=".$_GET['finder_id']);
        $objUser = kernel::single("partner_user");
        $this->end($objUser->save($_POST['data'],$strMsg),$strMsg);
    } // end function save

    // 启用&停用
    public function status($status) {
        $this->checkPermission("ecae_user_batch_status");
        $this->begin("index.php?app=partner&ctl=admin_user&act=index&finder_id=".$_GET['finder_id']);
        $objUser = kernel::single("partner_user");
        $this->end($objUser->setStatus($_POST,$status,$strMsg),$strMsg);
    } // end function status
    
    // change密码
    public function password($user_id) {
        $this->checkPermission("ecae_user_password");
        if(isset($_POST['data']) && $_POST['data']) {
            $this->begin("index.php?app=partner&ctl=admin_user&act=index&finder_id=".$_GET['finder_id']);
            $objUser = kernel::single("partner_user");
            $this->end($objUser->changePassword($_POST['data'],$strMsg),$strMsg);
        } else {
            $this->pagedata["user_id"] = $user_id;
            $this->display("admin/user/change_password.html");
        }
    }// end function password

    public function createnum() {
        $this->checkPermission("ecae_user_site_create");
        if($_POST["data"]) {
            $this->begin("index.php?app=partner&ctl=admin_user&act=index&finder_id=".$_GET['finder_id']);
            $this->end(kernel::single("partner_user")->setCreateNum($_POST['data'],$strMsg),$strMsg);
        } else {
            $this->pagedata['post'] = serialize($_POST);
            $this->display("admin/user/createnum.html");
        }
    } // end function createnum

}// end class
