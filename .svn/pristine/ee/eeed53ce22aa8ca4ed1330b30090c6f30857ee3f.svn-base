<?php
class yunmall_ctl_admin_setting extends desktop_controller {
    
    public function index($base = true) {
        $this->pagedata["data"] = array(
            "resource" => $this->app->getConf("node.resource"),
            "event"    => $this->app->getConf("node.event"),
            "notice"   => $this->app->getConf("node.notice"),
        ); 
        
        if($base) {
            $this->page("admin/setting/frame.html");
        } else {
            $this->display("admin/setting/basic.html");
        }
    } // end function index

    public function ecae() {
        $this->pagedata["data"] = array(
            "package" => $this->app->getConf("ecae.package"),
            "app"     => $this->app->getConf("ecae.app"),
        );
        $this->display("admin/setting/ecae.html");
    } // end function ecae


    public function save() {
        switch($_POST['action']) {
            case "node":
            case "ecae":
                foreach($_POST["data"] as $key => $value) {
                    $this->app->setConf($key,$value);
                }
                $this->index(false);
                break;
            default:
                die("what action?");
        }
    } // end function save

}// end class 
