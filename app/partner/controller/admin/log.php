<?php
class partner_ctl_admin_log extends partner_controller {
    
    public function index($site_id) {
        $this->checkPermission("ecae_site_log");
        $objSite = kernel::single("ecaeapi_site");
        
        $this->pagedata["publish"]  = $objSite->deploy_label($site_id);
        $this->pagedata["sections"] = $this->_get_section();
        $this->pagedata['site_id']  = $site_id;
        $this->pagedata['time_now'] = strtotime("-30 minute");
        $this->singlepage("admin/site/log/index.html");     
    } // end function index    

    public function search($site_id,$version,$offset = ""){
        if(empty($site_id)) die(app::get("partner")->_("站点不能为空"));
        if(empty($version)) die(app::get("partner")->_("请选选择发布点"));

        $arrFilter = $_POST['data'];
        $limit     = $_POST['data']['limit'];
        /*
        $page      = intval($page);
        $page      = ($page)? $page : 1;
        $offset    = ($page -1) * $limit;*/

        // 获取数字ID
        $arrSite = kernel::single("ecaeapi_site")->get_info($site_id);
        $arrFilter['site_id'] = $arrSite['id'];

        $arrFilter['version'] = $version;

        $arrLog = $this->app->model('log')->getList("*",$arrFilter,$offset,$limit);;

        $this->pagedata['log']       = $arrLog['data'];
        $this->pagedata['type']      = $arrFilter['search_type'];
        $this->pagedata['limit']     = $limit;
        $this->pagedata['offset']    = $offset;
        $this->pagedata['page']      = $arrLog['page']; // pre next

        $arrData["log"] = $this->fetch("admin/site/log/log.html");
        $arrData["page"] = $this->fetch("admin/site/log/page.html");
        die(json_encode($arrData));
    } // end function search
    
    private function _get_section() {
         return array(
                 'nginx' => array(
                                'label'=>'HTTP',
                                'file'=>'admin/site/log/log_nginx.html'
                            ),
                 'php'   => array(
                                'label'=>'PHP',
                                'file'=>'admin/site/log/log_php.html'
                            ),
                 'rdc'   => array(
                                'label'=>'MYSQL',
                                'file'=>'admin/site/log/log_rdc.html'
                            )

             );
    } // end function getSection

    public function ecae($site_id,$offset) {
       // $page  = intval($page);
       // $page  = ($page)? $page : 1;

        $limit  = 10;
        $arrLog = kernel::single("partner_log")->getEcaeLog($site_id,$offset,$limit);
        $this->pagedata['log']     = $arrLog['data']; 
        $this->pagedata['limit']   = $limit; 
        $this->pagedata['offset']  = $offset;
        $this->pagedata['site_id'] = $site_id;
        $this->pagedata['page']    = $arrLog['page'];

        $arrData["log"] = $this->fetch("admin/site/log/detail_data.html");
        $arrData["page"] = $this->fetch("admin/site/log/detail_page.html");
        die(json_encode($arrData));
    } // end function ecae 

} // end class
