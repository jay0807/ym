<?php
class yunmall_finder_user {
    
    public function __construct($app) {
        $this->app    = $app;
        $this->render = $this->app->render();
    }
    
    public $detail_basic = '会员信息';
    public function detail_basic($user_id){
        $arrData = $this->app->model('user')->dump(array("id"=>$user_id));
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/user/detail_basic.html');
    } // end function detail_basic
    
    public $column_op       = '操作';
    public $column_op_order = 1;
    public function column_op($row) {
        $arrLink = $this->_link($row); 

        //$objController = kernel::single("yunmall_controller");
        //foreach($arrLink as $key => $row) {
        //    if(!$objController->checkPermission("ecae_user_".$key,true))  unset($arrLink[$key]);
        //}
        
        $this->render->pagedata['arr_link'] = $arrLink; 
        return $this->render->fetch("admin/common/column.html");
    } // end function column_op

    private function _link($row) {
        return array(
            'edit' => array(
                "label"  => app::get("yunmall")->_("编辑"),
                "href"   => "index.php?app=yunmall&ctl=admin_user&act=edit&p[0]=".$row['id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "dialog::{title:'".app::get('yunmall')->_('修改会员')."(".$row['taobao_nick'].")',width:650,height:350}",
            ),
        );
    } // end function _link 

}// end class
