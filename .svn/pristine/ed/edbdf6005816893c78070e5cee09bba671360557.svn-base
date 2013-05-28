<?php
class partner_ctl_admin_domain extends partner_controller {
    
    public function index() {
        $this->checkPermission("ecae_site_domain");
        $actions = array();
        if($this->checkPermission("ecae_domain_add",true)) {
            $actions[]=    array(
                    'label'  => app::get('partner')->_('独立域名绑定'),
                    'href'   => 'index.php?app=partner&ctl=admin_domain&act=add',
                    'target' => 'dialog::{title:\''.app::get('partner')->_('独立域名绑定').'\',width:560,height:240}'
            );
        }
        if($this->checkPermission("ecae_domain_status",true)) {
            $actions[] = array(
                    'label'   => app::get('partner')->_('启用域名'),
                    'submit'  => 'index.php?app=partner&ctl=admin_domain&act=status&p[0]=1',
                    'confirm' => app::get('partner')->_('确定启用选中的域名?'),
            );
            $actions[] = array(
                    'label'   => app::get('partner')->_('关闭域名'),
                    'submit'  => 'index.php?app=partner&ctl=admin_domain&act=status&p[0]=0',
                    'confirm' => app::get('partner')->_('确定关闭选中的域名?'),
            );
        }
        if($this->checkPermission("ecae_domain_delete",true)) {
            $actions[] = array(
                    'label'   => app::get('partner')->_('解除绑定'),
                    'submit'  => 'index.php?app=partner&ctl=admin_domain&act=delete',
                    'confirm' => app::get('partner')->_('确定将选中的域名解除与站点的绑定关系?'),
            );
        }

        $this->finder('partner_mdl_domain',array(
            'title'               => '域名列表',
            'actions'             => $actions,
            'use_buildin_filter'  => true,
            'use_buildin_recycle' => false,
            'use_view_tab'        => true,
            //'use_buildin_export'=>true,
        ));
    } // end function index

    // 状态修改
    public function status($status) {
        $this->checkPermission("ecae_domain_status");
        $this->begin("index.php?app=partner&ctl=admin_domain&act=index&finder_id=".$_GET['finder_id']);
        $this->end(kernel::single("partner_domain")->setStatus($_POST,$status,$strMsg),$strMsg);
    } // end function status

    // delete 解除绑定
    public function delete() {
        $this->checkPermission("ecae_domain_delete");
        $this->begin("index.php?app=partner&ctl=admin_domain&act=index&finder_id=".$_GET['finder_id']);
        $this->end(kernel::single("partner_domain")->delete($_POST,$strMsg),$strMsg);
    } // end function status

    public function add() {
        $this->checkPermission("ecae_domain_add");
        if(isset($_POST['data'])) {
            $this->begin("index.php?app=partner&ctl=admin_domain&act=index&finder_id=".$_GET['finder_id']);
            $site_id = $_POST['data']['site_id'];
            $arrData = array(
                "domain" => $_POST['data']['domain'],
                "action" => "add"
            );
            $arrResult = kernel::single("partner_domain")->setDomain($site_id,$arrData);
            $flag = false;
            if(isset($arrResult['success'])) {
                $flag   = true;
                $strMsg = $arrResult['success'];
            } else {
                $strMsg = $arrResult['error'];
            }
            $this->end($flag,$strMsg);
        } else {
            $this->display("admin/site/domain_add.html");
        }
    } // end function add

    public function _views() {
        $objDomain = $this->app->model('domain');
        $sub_menu = array(
            array('label'=>app::get('partner')->_('全部'),'optional'=>false,'filter'=>null),
            array('label'=>app::get('partner')->_('审核中'),'optional'=>false,'filter'=>array('verify'=>1)),
        );
        foreach($sub_menu as $k=>$v){
            if($v['optional']==false){
                $show_menu[$k] = $v;
                $show_menu[$k]['filter'] = $v['filter']?$v['filter']:null;
                $show_menu[$k]['addon'] = $objDomain->count($v['filter']);
                $show_menu[$k]['href'] = 'index.php?app=partner&ctl=admin_domain&act=index&view='.($k).(isset($_GET['optional_view'])?'&optional_view='.$_GET['optional_view'].'&view_from=dashboard':'');
            }elseif(($_GET['view_from']=='dashboard') && $k== $_GET['view']){
                $show_menu[$k] = $v;
            }
        }
        return $show_menu;
    } // end function _views

}// end class 
