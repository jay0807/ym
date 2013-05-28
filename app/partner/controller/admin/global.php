<?php
class partner_ctl_admin_global extends partner_controller {
    
    public function setting($base = true) {
        $this->pagedata["data"] = $this->app->getConf("log"); 
        
        if($base) {
            $this->page("admin/global/frame.html");
        } else {
            $this->display("admin/global/basic.html");
        }
    } // end function setting

    public function save() {
        switch($_POST['action']) {
            case "log":
                $this->app->setConf("log",$_POST['data']['log']);
                $this->setting(false);
                break;
            default:
                die("what action?");
        }
    } // end function save

}// end class 
