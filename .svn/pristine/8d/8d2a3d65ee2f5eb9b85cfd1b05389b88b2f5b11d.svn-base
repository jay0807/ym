<?php
class yunmall_ctl_admin_user extends desktop_controller {

    public function index() {
        $actions = array();
        $this->finder('yunmall_mdl_user',array(
            'title'               => '云猫用户列表',
            'actions'             => $actions,
            'use_buildin_filter'  => true,
            'use_buildin_recycle' => false,
            'use_view_tab'        => true,
            //'use_buildin_export'=>true,
        ));
    } // end function index

    public function _views() {
        $objDomain = $this->app->model('user');
        $sub_menu = array(
            array('label'=>app::get('yunmall')->_('全部'),'optional'=>false,'filter'=>null),
            //array('label'=>app::get('yunmall')->_('审核中'),'optional'=>false,'filter'=>array('verify'=>1)),
        );
        foreach($sub_menu as $k=>$v){
            if($v['optional']==false){
                $show_menu[$k] = $v;
                $show_menu[$k]['filter'] = $v['filter']?$v['filter']:null;
                $show_menu[$k]['addon'] = $objDomain->count($v['filter']);
                $show_menu[$k]['href'] = 'index.php?app=yunmall&ctl=admin_user&act=user&view='.($k).(isset($_GET['optional_view'])?'&optional_view='.$_GET['optional_view'].'&view_from=dashboard':'');
            }elseif(($_GET['view_from']=='dashboard') && $k== $_GET['view']){
                $show_menu[$k] = $v;
            }
        }
        return $show_menu;
    } // end function _views

    public function edit($id) {
        if($_POST) {
            $this->begin("index.php?app=yunmall&ctl=admin_user&act=index&finder_id=".$_GET['finder_id']);
            $this->end(kernel::single("yunmall_taobao")->update($_POST['data'],$strMsg),$strMsg);
        } else {
            $arrData = $this->app->model('user')->dump(array("id"=>$id));
            $this->pagedata['data']  = $arrData; 
            $this->pagedata['id']    = $id; 
            $this->display("admin/user/edit.html");
        }
    } // end function edit


} // end class
