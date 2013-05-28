<?php
class yunmall_ctl_site_event extends yunmall_controller {

    public function index($id = null) {
        $id = $this->explode_param($id);
        if($id) {
            $objEvent = $this->app->model("event");         
            if($data = $objEvent->getOne($id)) {
                $this->pagedata["data"] = $data;
                $this->set_tmpl_file("event.html");
                $this->page("site/event/detail.html");
            } else {
                $this->page404();
            }
        } else {
            $objEvent = $this->app->model("event");
            $this->pagedata["data"] = $objEvent->getList();
            $this->page("site/event/list.html");
        }
    } // end function index

} // end class
