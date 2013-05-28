<?php
class partner_filter_package extends partner_filter {

    public function filter($arrFilter) {
        $arrResult = array();
        if(empty($arrFilter) || !is_array($arrFilter)) return $arrResult;    

        // 如果存在id 直接按应用ID查询
        if(isset($arrFilter['pakcage_id']) && $arrFilter['package_id']) {
            if(is_array($arrFilter['package_id'])) {
                $id = trim($arrFilter['package_id'][0]); 
            } else {
                $id = trim($arrFilter['package_id']);
            }
            $arrResult["id"] = $id;
        }
        // 名称
        if(isset($arrFilter['name']) && trim($arrFilter['name'])) {
            $arrResult["name"] = trim($arrFilter['name']); 
        }
        // 状态
        if(isset($arrFilter['status']) && trim($arrFilter['status'])) {
            $arrResult["status"] = trim($arrFilter['status']); 
        }

        return $this->tidy($arrResult);
    } // end function filter

}// end class 
