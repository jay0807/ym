<?php
class partner_filter_domain extends partner_filter {
 
    public function filter($arrFilter) {
        $arrResult = array();
        if(empty($arrFilter) || !is_array($arrFilter)) return $arrResult;    

        // 如果存在domain 
        if(isset($arrFilter['id']) && $arrFilter['id']) {
            $arrResult['id'] = str_replace(array("http://","https://"),"",$arrFilter['id']);
            return $this->tidy($arrResult);
        }

        // 如果存在domain 
        if(isset($arrFilter['domain']) && $arrFilter['domain']) {
            $arrResult['domain'] = str_replace(array("http://","https://"),"",$arrFilter['domain']);
        }

        // 站点ID
        if(isset($arrFilter['site_id']) && $arrFilter['site_id']) {
            if(is_array($arrFilter['site_id'])) {
                $sitename = trim($arrFilter['site_id'][0]);
            } else {
                $sitename = trim($arrFilter['site_id']);
            }
            $arrResult['site_id'] = $sitename;
        }

        // 开启状态
        if(isset($arrFilter['status']) && trim($arrFilter['status']) != "") {
            $arrResult["status"] = ($arrFilter['status'])? "true" : "false"; 
        }
        // 审核状态
        if(isset($arrFilter['verify']) && trim($arrFilter['verify']) != "") {
            $arrResult["verify"] = ($arrFilter['verify'])? "false" : "true";
        }

        return $this->tidy($arrResult);
    } // end function filter

    public function sort($sort) {
        return null;
    } // end function sort

}// end class 
