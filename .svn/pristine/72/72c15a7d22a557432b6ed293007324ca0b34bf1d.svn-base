<?php
class partner_application {

    public function __construct($app) {
        $this->app = $app;
    }

    public function save($arrData,&$strMsg) {
        if($strMsg = $this->verify($arrData)) return false;

        $objApp = kernel::single("ecaeapi_application");

        if(empty($arrData['app_id'])) {
            $strMsg = app::get("partner")->_("添加失败");
            if(!$app_id = $this->_add($arrData,$strMsg)) return false;
        } else {// edit part
            $app_id = $arrData['app_id'];
            $arrApp = array(
                array("price",intval($arrData['price'])),
                array("path_rev",array($arrData['path'],$arrData['ver'])),
                array("name",htmlspecialchars(trim($arrData['name']))),
            );
            $objApp->set_info($app_id,$arrApp);
        }
        // app status
        if(isset($arrData['app_id']) || $arrData['status'] == 'down') {
            $objApp->set_status($app_id,$arrData['status']);
        }
        // app intro
        $objApp->set_intro($app_id,htmlspecialchars($arrData['intro']));

        $strMsg = ($arrData['app_id'])? app::get("partner")->_("修改成功") : app::get("partner")->_("添加成功");
        return true;
    } // end function save

    // 创建资源
    private function _add($arrData,&$strMsg) { 
        try{
            $objApp = kernel::single("ecaeapi_application");
            return $objApp->add(trim($arrData['name']),
                                $arrData['site_id'],    
                                trim($arrData['path']),
                                trim($arrData['ver']),
                                $arrData['scm_type'],
                                intval($arrData["price"]),
                                array());
        } catch(Exception $e) {
            $strMsg = $e->getMessage();
            return false;
        }
    } // end function add

    // 验证
    private function verify($arrData) {
        if(!isset($arrData['name'])     || empty($arrData['name']))       return app::get("partner")->_("应用名称不能为空");
        if(!isset($arrData['scm_type']) || empty($arrData['scm_type']))   return app::get("partner")->_("请选择类型");
        if(!isset($arrData['site_id'])  || empty($arrData['site_id']))    return app::get("partner")->_("请选择源站点");
        if(!isset($arrData['path'])     || empty($arrData['path']))       return app::get("partner")->_("路径不能为空");
        if(!preg_match("/^\/[a-zA-Z0-9\/-]+/",$arrData['path']))           return app::get("partner")->_("路径只能为字母,数字或者/组成");
        if(!isset($arrData['ver'])      || empty($arrData['ver']))        return app::get("partner")->_("版本不是能空");
        if(intval($arrData['ver']) <= 0 )                                 return app::get("partner")->_("版本不能小于等于0");
        if(!isset($arrData['price'])    || trim($arrData['price']) == "") return app::get("partner")->_("价格不能为空"); 
        if(intval($arrData['price']) <= 0 )                               return app::get("partner")->_("价格不能小于等于0");

        return false;
    } // end function verify

    // 设置状态(上架&&下架)
    public function setStatus($arrData,$status,&$strMsg) {
        if(!isset($arrData['id']) || count($arrData['id']) > 20) {
            $strMsg = app::get("partner")->_("一次处理最多20条数据!");
            return false;
        }

        $objApp = kernel::single("ecaeapi_application");
        foreach($arrData['id'] as $row) {
            $objApp->set_status($row,$status);
        }

        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function setStatus

} // end class
