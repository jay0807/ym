<?php
class partner_finder_site{
    
    public function __construct() {
        $this->app = app::get('partner');
        $this->render = $this->app->render();
    }
    
    public $detail_basic = '站点信息';
    public function detail_basic($site_id) {
        $objSite = $this->app->model('site');
        $arrData = $objSite->dump($site_id);
        
        //$arrData["app"]     = $objSite->modifier_app($arrData["app"]);
        //$arrData["package"] = $objSite->modifier_package($arrData["package"]);
        $arrData["scm_url"] = preg_replace("/:\d*\//","/",kernel::single("ecaeapi_site")->get_scm_url($site_id));

        $this->render->pagedata['data']   = $arrData;
        $this->render->pagedata['domain'] = kernel::single("partner_domain")->getDomainList($site_id);
        return $this->render->fetch('admin/site/detail_basic.html');
    } // end function detail_basic

    public $detail_publish = '站点发布';
    public function detail_publish($site_id) {
        $arrData = kernel::single("ecaeapi_site")->get_published($site_id);
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/site/detail_publish.html');
    } // end function detail_publish

    public $detail_domain = '站点域名';
    public function detail_domain($site_id) {
        $arrData = kernel::single("partner_domain")->getDomainList($site_id);
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/site/detail_domain.html');
    } // end function detail_domain


    public $detail_user = '站点用户';
    public function detail_user($site_id) {
        $arrData = kernel::single("ecaeapi_site")->list_user($site_id);
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/site/detail_user.html');
    } // end function detail_user

    public $detail_vars = '站点变量';
    public function detail_vars($site_id) {
        $arrEnv = kernel::single("partner_site")->getEnv($site_id,true);

        $this->render->pagedata['data'] = $arrEnv; 
        return $this->render->fetch('admin/site/detail_vars.html');
    } // end function detail_vars

    public $detail_log = '管理记录';
    public function detail_log($site_id) {
        $limit  = 10;
        $offset = "";
        $arrLog = kernel::single("partner_log")->getEcaeLog($site_id,$offset,$limit);;

        $this->render->pagedata['log']     = $arrLog['data']; 
        $this->render->pagedata['page']    = $arrLog['page'];
        $this->render->pagedata['limit']   = $limit; 
        $this->render->pagedata['offset']  = $offset;
        $this->render->pagedata['site_id'] = $site_id;

        return $this->render->fetch('admin/site/detail_log.html');
    } // end function detail_log

    public $column_op       = '操作';
    public $column_op_order = 1;
    public function column_op($row) {
        $arrLink =  $this->_link($row);

        if($row['type'] != "paas")   unset($arrLink["user"]);
        if($row['path'] == "static") unset($arrLink["publish"]);

        $objController = kernel::single("partner_controller");
        foreach($arrLink as $key => $row) {
            if(!$objController->checkPermission("ecae_site_".$key,true))  unset($arrLink[$key]);
        }

        $this->render->pagedata['arr_link'] = $arrLink; 
        return $this->render->fetch("admin/common/column.html");
    } // end function column_op

    private function _link($row) {
        return array(
            'edit' => array(
                "label"  => app::get("partner")->_("编辑"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=edit&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "dialog::{title:'".app::get('partner')->_('修改站点')."(".$row['site_id'].")',width:700,height:250}",
            ),
            'domain' => array(
                "label"  => app::get("partner")->_("域名管理"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=domain&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank"
                //"target" => "dialog::{title:'".app::get('partner')->_("域名管理")."(".$row['site_id'].")',width:500,height:280}",
            ),
            'user' => array(
                "label"  => app::get("partner")->_("成员管理"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=user&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                //"target"=>"dialog::{title:'".app::get('partner')->_("成员管理")."(".$row['domain'].")',width:800,height:300}",
                "target" => "_blank"
            ),
            /*
            'job'=>array(
                "label"  =>  app::get("partner")->_("任务管理"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=job&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),*/ 
            'publish' => array(
                "label"  => app::get("partner")->_("发布管理"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=publish&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
                //"target" => "dialog::{title:'".app::get('partner')->_("发布管理")."(".$row['domain'].")',width:720,height:500}",
            ),    
            'ip' => array(
                "label"  => app::get("partner")->_("IP防火墙"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=ip&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "dialog::{title:'".app::get('partner')->_("IP过滤")."(".$row['domain'].")',width:500,height:320}",
            ),
            'log' => array(
                "label"  => app::get("partner")->_("日志管理"),
                //"href"   => "index.php?app=desktop&ctl=default&act=alertpages&goto=".urlencode("index.php?app=partner&ctl=admin_site&act=log&p[0]=".$row['site_id']."&nobuttion=1")."&finder_id=".$_GET['_finder']['finder_id'],
                "href"   => "index.php?app=partner&ctl=admin_log&act=index&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),
            'phpini' => array(
                "label"  => app::get("partner")->_("PHP设置"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=phpini&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),
            'nginx' => array(
                "label"  => app::get("partner")->_("WEB设置"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=nginx&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),
            'mysql' => array(
                "label"  => app::get("partner")->_("数据库管理"),
                "href"   => "index.php?app=partner&ctl=admin_site&act=mysql&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),
            'deadline'=>array(
                "label"=>app::get("partner")->_("开通周期"),
                "href"=>"index.php?app=partner&ctl=admin_site&act=deadline&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target"=>"dialog::{title:'".app::get('partner')->_("开通周期")."(".$row['domain'].")',width:540,height:160}",
            ),
            'streamlog' => array(
                "label"  => app::get("partner")->_("站点任务日志"),
                "href"   => "index.php?app=partner&ctl=admin_streamlog&act=index&p[0]=".$row['site_id']."&finder_id=".$_GET['_finder']['finder_id'],
                "target" => "_blank",
            ),
        );
    } // end function _link

} // end class
