<?php
class partner_ctl_admin_agentapi extends partner_controller {
    
    public function log() {
        $this->page("admin/agentapi/log.html");
    } // end function setting


    public function search_log($time){
        $arrFilter = array('site_id'=>ECAE_API_AGENT, 'version'=>'noversion', 'search_type'=>'ecaeagentapi', 'type'=>'info');
        $limit = $_GET['limit']?$_GET['limit']:15;
        $offset = $_GET['offset']?$_GET['offset']:0; 
        $this->pagedata['limit'] = $limit;
        $arrFilter['time'] = $time;
        $arrLog = $this->app->model('log')->getList("*",$arrFilter,$offset,$limit);
        $this->pagedata['log'] = $arrLog;
        $this->pagedata['search_time'] = $time;
        $this->display("admin/agentapi/log.html");
    }

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

}
