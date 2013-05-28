<?php
class partner_user {

    public function __construct($app) {
        $this->app = $app;
    }

    public function save($arrData,&$strMsg) {
        // 验证
        if($strMsg = $this->verify($arrData)) return false;
        
        try{
            $objUser = $this->app->model("user");
            if(!$objUser->save($arrData)) {
                $strMsg = ($arrData['id'])? app::get("partner")->_("保存失败") : app::get("partner")->_("添加失败");
                return false;
            }
            $strMsg = ($arrData['id'])? app::get("partner")->_("保存成功") : app::get("partner")->_("添加成功");
            return true;
        } catch(Exception $e) {
            $strMsg = $e->getMessage();
            return false;
        }
    } // end function save

    /**
     * 是否已存在用户ID(也是用户名称)
     * 
     * @param string $user_id // 用户名
     * @return bool
     */
    public function isExist($user_id) {
        $arrData = kernel::single("ecaeapi_user")->is_exist($user_id);
        return !empty($arrData);
    }

    // 验证
    private function verify($arrData) {
        // 用户名
        if(!isset($arrData['username']) || empty($arrData['username']) ) return app::get("partner")->_("用户名不能为空");
        if(empty($arrData['id'])) { // 添加时
            // 是否存在
            if($this->isExist(trim($arrData['username']))) {
                return app::get("partner")->_("用户名已存在!");
            }
        }        
        // password
        if(isset($arrData['password'])) {
            $arrData['password'] = trim($arrData['password']);
            if($arrData['password'] == "") {
                return app::get("partner")->_("密码不能为空"); 
            }
            if($arrData['password'] != trim($arrData['confirm_password'])) {
                return app::get("partner")->_("密码与确认密码不符");
            }
        }
        // email
        if(!isset($arrData['email']) || empty($arrData['email'])) {
            return app::get("partner")->_("邮箱不能为空");
        }
        return false;
    }// end function verify

    // 设置状态
    public function setStatus($arrData,$status,&$strMsg) {
        $arrResult = array();
        switch(true) {
            case isset($arrData['user']) :
                $arrResult = $arrData['user'];
                break;
            case isset($arrData["isSelectedAll"]):
                $arrResult = $this->getUserID($arrData);
                break; 
        }
        if(empty($arrResult)) {
            $strMsg = app::get("partner")->_("请选择你要处理的数据!"); return false;
        }
        $objUser = kernel::single("ecaeapi_user");
        foreach($arrResult as $row) {
            $objUser->setStatus($row,$status);
        }

        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function setStatus

    public function getUserID($arrWhere) {
        $arrResult = array();
        $arrTemp   = $this->app->model("user")->getList("*",$arrWhere);
        foreach($arrTemp as $row) {
            $arrResult[] = $row['user'];
        }
        return $arrResult;
    } // end function getUserID
     
    // 修改密码
    public function changePassword($arrData,&$strMsg = null) {
        // 验证
        if(empty($arrData)) {
            $strMsg = app::get("partner")->_("数据不能为空"); return false;
        }
        $arrData['password'] = trim($arrData['password']);
        if($arrData['password'] == "") {
            $strMsg = app::get("partner")->_("密码不能为空"); return false;
        }
        if($arrData['password'] != $arrData['confirm_password']) {
            $strMsg = app::get("partner")->_("密码与确认密码不符"); return false;
        }

        $objUser = kernel::single("ecaeapi_user");
        if(!$arrUser = $objUser->dump($arrData['user_id'])) {
            $strMsg = app::get("partner")->_("用户信息错误"); return false;
        }
        //  genPassword failure
        if(!$password = $objUser->gen_password($arrData['user_id'])) {
            $strMsg = app::get("partner")->_("重置密码失败"); return false;
        }
        //  changePassword failure
        if(!$objUser->change_password($arrData['user_id'],$password,$arrData['password'])) {
            $strMsg = app::get("partner")->_("修改密码失败"); return false;
        }
        return true;
    } // end function changePassword

    // 设置可创建站点数
    public function setCreateNum($arrData,&$strMsg) {
        // 验证
        if(empty($arrData) || !isset($arrData['post']) || empty($arrData['post'])) {
            $strMsg = app::get("partner")->_("数据不能为空"); return false;
        }
        $arrPost = unserialize($arrData['post']);
        if(!isset($arrPost['user']) || count($arrPost['user']) > 20) {
            $strMsg = app::get("partner")->_("一次处理最多20条数据!"); return false;
        }

        $objUser = kernel::single("ecaeapi_user");
        foreach($arrPost['user'] as $row) {
            $objUser->set_alternation($row,$arrData['num'],$arrData['period'],$arrData['period_type']);
        }

        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function setStatus

} // end class
