<?php
class partner_filter_application extends partner_filter {

    public function filter($arrFilter) {
        $arrResult = array();
        if(empty($arrFilter) || !is_array($arrFilter)) return $arrResult;
        
        $arrData = array();        
        if(isset($arrFilter['status']) && trim($arrFilter['status']) != "") {
            $arrData["status"] = trim($arrFilter['status']); 
        }    
        if(isset($arrFilter['type']) && trim($arrFilter['type'])) {
            $arrData["type"] = trim($arrFilter['type']); 
        }
        if(isset($arrFilter['name']) && trim($arrFilter['name'])) {
            $arrData["name"] = trim($arrFilter['name']); 
        }
        // 如果存在id 直接按应用ID查询
        if(isset($arrFilter['id']) && $arrFilter['id']) {
            if(is_array($arrFilter['id'])) {
                $id = trim($arrFilter['id'][0]); 
            } else {
                $id = trim($arrFilter['id']);
            }
            $arrData["id"] = $id;
        }

        return $this->tidy($arrData);
    } // end function filter

}// end class 
