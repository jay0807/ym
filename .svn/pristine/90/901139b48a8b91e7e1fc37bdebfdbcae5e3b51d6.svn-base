<?php
class partner_finder_application{
    
    public function __construct() {
        $this->app = app::get('partner');
        $this->render = $this->app->render();
    }
    
    public $detail_basic = '应用信息';
    public function detail_basic($app_id){
        $arrData = $this->app->model('application')->dump($app_id);
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/application/detail_basic.html');
    } // end function detail_basic

    public $column_op       = '操作';
    public $column_op_order = 1;
    public function column_op($row) {
        if(kernel::single("partner_controller")->checkPermission("ecae_application_edit",true)) {
            return "<a href='index.php?app=partner&ctl=admin_application&act=edit&p[0]="
                .$row['id']."&finder_id=".$_GET['_finder']['finder_id']
                ."' target=\"dialog::{title:'".app::get('platform')->_('编辑应用')."',width:720,height:420}\">编辑</a>";
        }    
    } // end function column_op

} // end class 
