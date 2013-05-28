<?php
class partner_finder_package{
    
    public function __construct() {
        $this->app = app::get('partner');
        $this->render = $this->app->render();
    }

    public $detail_basic = '基本信息';
    public function detail_basic($id){ 
        $this->render->pagedata["data"] = $this->app->model("package")->dump($id);
        return $this->render->fetch('admin/package/detail_basic.html');
    } // end function detail_basic
    
    public $detail_addon = '服务信息';
    public function detail_addon($id){
        $this->render->pagedata["data"] = $this->app->model("package")->dump($id);
        return $this->render->fetch('admin/package/detail_addon.html');
    } // end function detail_addon


} // end class
