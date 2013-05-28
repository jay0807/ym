<?php
class partner_filter_user extends partner_filter {

    public function filter($arrFilter) {
        $arrResult = array();
        if(empty($arrFilter) || !is_array($arrFilter)) return $arrResult;
        
        // 如果存在email 直接按邮箱查询
        if(isset($arrFilter['email']) && trim($arrFilter['email'])) {
            $arrResult["email"] = trim($arrFilter['email']);
        }
        // 如果存在user 直接按用户名查询
        if(isset($arrFilter['user']) && $arrFilter['user']) {
            if(is_array($arrFilter['user'])) {
                $username = trim($arrFilter['user'][0]);
            } else {
                $username = trim($arrFilter['user']);
            }
            $arrResult["user"] = $username;
        }
        // 状态
        if(isset($arrFilter['status']) && trim($arrFilter['status']) != "") {
            $arrResult["status"] = $arrFilter['status']? "true" : "false"; 
        }
        if(isset($arrFilter['creattime']) && trim($arrFilter['creattime'])) {
            if($temp = $this->tidy_time($arrFilter,'creattime')) $arrResult['creattime'] = $temp;
        }

        return $this->tidy($arrResult);
    } // end function filter

    public function sort($sort) {
        if($sort) {
            $arrTemp = explode(" ",trim($sort));
            if(count($arrTemp) == 2 && in_array(trim($arrTemp[0]),array("creattime","sitenum"))) {
                return array(trim($arrTemp[0]),trim($arrTemp[1]));
            }
        }
        return null;
    } // end function sort

}// end class 
