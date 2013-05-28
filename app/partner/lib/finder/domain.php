<?php
class partner_finder_domain{
    
    public function __construct() {
        $this->app = app::get('partner');
        $this->render = $this->app->render();
    }

    public $detail_basic = '站点信息';
    public function detail_basic($id) {
        list($site_id,$domain) = explode("|",$id);
        $objSite = $this->app->model('site');
        $arrData = $objSite->dump($site_id);
        
        //$arrData["app"]     = $objSite->modifier_app($arrData["app"]);
        //$arrData["package"] = $objSite->modifier_package($arrData["package"]);
        $arrData["scm_url"] = kernel::single("ecaeapi_site")->get_scm_url($site_id);

        $this->render->pagedata['data']   = $arrData;
        $this->render->pagedata['domain'] = kernel::single("partner_domain")->getDomainList($site_id);
        return $this->render->fetch('admin/site/detail_basic.html');
    } // end function detail_basic
    
    public $detail_refer = '相关域名';
    public function detail_refer($id) {
        list($site_id,$domain) = explode("|",$id);
        $arrData = kernel::single("partner_domain")->getDomainList($site_id);
        
        $this->render->pagedata['data'] = $arrData;
        return $this->render->fetch('admin/site/detail_domain.html');
    } // end function detail_addon


} // end class
