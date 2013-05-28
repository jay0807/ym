<?php
class partner_finder_user {
    
    public function __construct($app) {
        $this->app    = $app;
        $this->render = $this->app->render();
    }
    
    public $detail_basic = '会员信息';
    public function detail_basic($user_id){
        $arrData = $this->app->model('user')->dump($user_id,false);
        
        $this->render->pagedata['alter'] = kernel::single("ecaeapi_user")->get_alternation($user_id);
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/user/detail_basic.html');
    } // end function detail_basic
    
    public $detail_site = '站点相关';
    public function detail_site($user_id) {
        $arrData = $this->app->model('user')->getSiteList($user_id);

        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/user/detail_site.html');
    } // end function detail_site
    
    public $column_op       = '操作';
    public $column_op_order = 1;
    public function column_op($row) {
        $arrLink = $this->_link($row); 

        $objController = kernel::single("partner_controller");
        foreach($arrLink as $key => $row) {
            if(!$objController->checkPermission("ecae_user_".$key,true))  unset($arrLink[$key]);
        }
        
        $this->render->pagedata['arr_link'] = $arrLink; 
        return $this->render->fetch("admin/common/column.html");
    } // end function column_op

    private function _link($row) {
        return array(
            'edit' => array(
                "label"  => app::get("partner")->_("编辑"),
                "href"   => "index.php?app=partner&ctl=admin_user&act=edit&p[0]=".$row['user']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "dialog::{title:'".app::get('partner')->_('修改会员')."(".$row['user'].")',width:650,height:350}",
            ),    
            'password'=>array(
                "label"  => app::get("partner")->_("修改密码"),
                "href"   => "index.php?app=partner&ctl=admin_user&act=password&p[0]=".$row['user']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "dialog::{title:'".app::get('partner')->_("修改密码")."(".$row['user'].")',width:500,height:160}",
            ),
        );
    } // end function _link 

}// end class
