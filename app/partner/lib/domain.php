<?php
class partner_domain {

	public function __construct($app) {
		$this->app = $app;
	}

    // 获取站点所有域名
    public function getDomainList($site_id) {
        if(empty($site_id) ) return array();
        $objSite = kernel::single("ecaeapi_site");

        $arrTemp = $objSite->list_domain($site_id);
        $default = $objSite->default_domain($site_id);
        $arrResult = array();
        $arrResult[] = array(
            "url"     => $default,
            "default" => true,
            "active"  => true,
            "verify"  => true,
        );
        foreach($arrTemp as $row) {
            $arrResult[] = array(
                "url"     => $row['domain'],
                "default" => false,
                "active"  => $row['active'],
                "verify"  => $row['verify_status'],
            );
        }
        return $arrResult;
    }// end function getDomainList

    // 站点绑定域名
    public function setDomain($site_id,$arrData) {
        if(empty($site_id) || empty($arrData) || !isset($arrData['domain']) || !isset($arrData["action"]) ) 
            return array('error'=>app::get("partner")->_("数据不能为空"));
        $action = $arrData['action'];
        if(empty($action) || !in_array($action,array("add","delete","active","deactive"))) 
            return array('error'=>app::get("partner")->_("非法操作"));
        
        $objSite = kernel::single("ecaeapi_site");
        $domain = strtolower(trim($arrData['domain']));

        // 权限检测
        $objController = kernel::single("partner_controller");
        if(!$objController->checkPermission("ecae_domain_".$action,true)) return array("error"=>app::get("partner")->_("没有相关操作权限")); 
        try{
            switch($action) {
            case "add":
                    if(!preg_match("/^[a-z0-9_\.-]+$/",$domain)) return array("error"=>app::get("partner")->_("域名应只包含a-z0-9_.-的字符"));
                    kernel::single("ecaeapi_domain")->add($site_id,$domain);
                    return array("success"=>app::get("partner")->_("域名绑定成功"));
            case "active":
                    kernel::single("ecaeapi_domain")->active($site_id,$domain);
                    return array("success"=>app::get("partner")->_("域名激活成功"));
            case "delete":
                    kernel::single("ecaeapi_domain")->delete($site_id,$domain);
                    return array("success"=>app::get("partner")->_("域名删除成功"));
            case "deactive":
                    kernel::single("ecaeapi_domain")->deactive($site_id,$domain);
                    return array("success"=>app::get("partner")->_("域名关闭成功"));
            }    
        } catch(Exception $e) { return array("error"=>$e->getMessage()); }
    } // function setDomain

    // 编辑状态
    public function setStatus($arrData,$status,&$strMsg) {
        if(!isset($arrData['id']) || count($arrData['id']) > 20) {
            $strMsg = app::get("partner")->_("一次处理最多20条数据!"); return false;
        }

        $action    = $status? "active" : "deactive";
        $objDomain = kernel::single("ecaeapi_domain");
        foreach($arrData['id'] as $row) {
            list($site,$domain) = explode("|",$row);
            $objDomain->$action($site,$domain);
        }

        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function setStatus

    // 解除绑定关系
    public function delete($arrData,&$strMsg) {
        if(!isset($arrData['id']) || count($arrData['id']) != 1) {
            $strMsg = app::get("partner")->_("一次处理最多解除一个域名的绑定!"); return false;
        }

        $objDomain = kernel::single("ecaeapi_domain");
        foreach($arrData['id'] as $row) {
            list($site,$domain) = explode("|",$row);
            try {
                $objDomain->delete($site,$domain);
            } catch(Exception $e) {
                $strMsg = $e->getMessage();
                return false;
            }
        }
        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function delete

} // end class
