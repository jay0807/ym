<?php
class yunmall_ctl_site_notice extends yunmall_controller {

    public function index($id = null) {
        $id = $this->explode_param($id);
        $objNotice = $this->app->model("notice");         
        if($id && $data = $objNotice->getOne($id)) {
            $this->pagedata["data"] = $data;
            $this->set_tmpl_file("notice.html");
            $this->page("site/notice/detail.html");
        } else {
            $this->page404();
        }
    } // end function index

} // end class
