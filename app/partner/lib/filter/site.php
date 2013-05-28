<?php
class partner_filter_site extends partner_filter {
    
    public function filter($arrFilter) {
        if(isset($arrFilter["view"])) {
            $arrView = kernel::single("partner_controller")->getView("partner_mdl_site",array("app"=>"partner","ctl"=>"admin_site","act"=>"index"));
            $arrFilter = array_merge($arrView[$arrFilter["view"]],$arrFilter);
        }
        $arrResult = array();
        if(empty($arrFilter) || !is_array($arrFilter)) return $arrResult;

        // 如果存在site_id 直接按站点ID查询
        if(isset($arrFilter['site_id']) && $arrFilter['site_id']) {
            if(is_array($arrFilter['site_id'])) {
                $sitename = trim($arrFilter['site_id'][0]);
            } else {
                $sitename = trim($arrFilter['site_id']);
            }
            return $this->tidy(array("domain"=>$sitename));
        }
        
        // 如果存在domain 直接按域名查询
        if(isset($arrFilter['domain']) && $arrFilter['domain']) {
            // 先按独立域名域名进行搜索
            $domain = str_replace(array("http://","https://"),"",$arrFilter['domain']);
            if($arrDomain = $this->app->model("domain")->getList("*",array("id"=>$domain))) {
                $arrResult['domain'] = $arrDomain[0]['site_id'];
            } else { // 按三级域名搜索,可以找到很多不同代理商的站点
                $arrTemp = explode(".",$domain);
                $arrResult['domain'] = $arrTemp[0];
            }
        } 
        
        // 如果存在name 直接按名字查询
        if(isset($arrFilter['name']) && trim($arrFilter['name'])) {
            $arrResult["name"] = trim($arrFilter['name']);
        }

        // 如果存在user 直接按创建者查询
        if(isset($arrFilter['user']) && trim($arrFilter['user'])) {
            // todo 可能以后要按所有者来搜索 这块代码在erlang修改 看看效率如果不行就放弃
            $arrResult["user"] =  trim($arrFilter['user']);
        }

        // 应用
        if(isset($arrFilter['application']) && trim($arrFilter['application'])) {
            $arrResult["app"] = trim($arrFilter['application']); 
        }
        // 状态
        if(isset($arrFilter['status']) && trim($arrFilter['status']) != "") {
            $arrResult["status"] = $arrFilter['status']? "true" : "false"; 
        }
        // 套餐
        if(isset($arrFilter['package']) && trim($arrFilter['package'])) {
            $arrResult["package"] = trim($arrFilter['package']); 
        }
        // 类型
        if(isset($arrFilter['type']) && trim($arrFilter['type'])) {
            $arrResult["type"] = trim($arrFilter['type']); 
        }
        // 路径版本号
        if(isset($arrFilter['version']) && trim($arrFilter['version'])) {
            $arrResult["version"] = trim($arrFilter['version']); 
        }

        if(isset($arrFilter['creattime']) && trim($arrFilter['creattime'])) {
            if($temp = $this->tidy_time($arrFilter,'creattime')) $arrResult['creattime'] = $temp;
        }
        if(isset($arrFilter['starttime']) && trim($arrFilter['starttime'])) {
            if($temp = $this->tidy_time($arrFilter,'starttime')) $arrResult['starttime'] = $temp;
        }
        if(isset($arrFilter['deadline']) && trim($arrFilter['deadline'])) {
            if($temp = $this->tidy_time($arrFilter,'deadline')) $arrResult['deadline'] = $temp;
        }

        return $this->tidy($arrResult);
    } // end function filter

    public function sort($sort) {
        if($sort) {
            $arrTemp = explode(" ",trim($sort));
            if(count($arrTemp) == 2 && in_array(trim($arrTemp[0]),array("creattime","starttime","deadline","version"))) {
                return array(trim($arrTemp[0]),trim($arrTemp[1]));
            }
        }
        return null;
    } // end function sort

}// end class 
