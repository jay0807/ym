<?php
class yunmall_ctl_site_resource extends yunmall_controller {

    public function index($id = null) {
        $id = $this->explode_param($id);
        if($id) {
            $objResource = $this->app->model("resource");         
            if($data = $objResource->getOne($id)) {
                $this->pagedata["data"] = $data;
                $this->set_tmpl_file("resource.html");
                $this->page("site/resource/detail.html");
            } else {
                $this->page404();
            }
        } else {
            $objResource = $this->app->model("resource");
            $this->pagedata["data"] = $objResource->getList();
            $this->page("site/resource/list.html");
        }
    } // end function index

} // end class
