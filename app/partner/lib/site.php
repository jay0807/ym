<?php
class partner_site {

    public function __construct($app) {
        $this->app = $app;
    }

    public function save($arrData,&$strMsg) {
        if($strMsg = $this->verify($arrData)) return false;
        try {
            if(!$this->app->model("site")->save($arrData)) {
                $strMsg = ($arrData['site_id'])? app::get("partner")->_("保存失败") : app::get("partner")->_("添加失败"); return false;
            } else {
                $strMsg = ($arrData['site_id'])? app::get("partner")->_("保存成功") : app::get("partner")->_("添加成功"); return true;
            }
        } catch(Exception $e) {
            $strMsg = $e->getMessage();
            return false;
        }
    } // end function save
    
    public function update($arrData,&$strMsg) {
        try{
            $site_id = trim($arrData['site_id']);
            $arrOption = array(
                array('title',     htmlspecialchars($arrData['title'])),
                array('desc',      htmlspecialchars($arrData['intro'])),
            );
            if(kernel::single("ecaeapi_site")->set_info($site_id,$arrOption)) {
                $strMsg = app::get("partner")->_("保存成功"); return true;
            } else {
                $strMsg = app::get("partner")->_("保存失败"); return false;
            }
        } catch(Exception $e) {
           $strMsg = $e->getMessage(); 
           return false;
        }
    } // end function update

    // 验证
    private function verify($arrData) {
        if(!isset($arrData['title'])   || empty($arrData['title']))   return app::get("partner")->_("站点名称不能为空"); 
        if(!isset($arrData['user_id']) || empty($arrData['user_id'])) return app::get("partner")->_("请选择所属会员");
        if(!isset($arrData['package']) || empty($arrData['package'])) return app::get("partner")->_("请选择套餐");
        if(!isset($arrData['period'])  || empty($arrData['period']))  return app::get("partner")->_("站点有效期不能为空");
        if(intval($arrData['period']) <= 0 )  return app::get("partner")->_("站点有效期必须为正整数");

        // 站点域名
        if(!isset($arrData['name']) || empty($arrData['name'])) return app::get("partner")->_("域名不能为空");
        // if(strlen($arrData['name']) < 3 || strlen($arrData['name']) > 20 || preg_match("/[^a-z0-9-]/i",$arrData['name'])) {
        if(preg_match("/[^a-z0-9][^a-z0-9-]/i",$arrData['name'])) {
            return app::get("partner")->_("域名为a-z,0-9,-的字符");
        }
        if(empty($arrData['site_id'])) { // 添加时
            $site = trim($arrData['name']);
            if($this->isExist($site)) return app::get("partner")->_("域名已存在!");
        } else {
            if("www" == $arrData['site_id'] && empty($arrData["status"]) ) {
                return app::get("partner")->_("不能自杀哦!");
            }
        }
        return false;
    } // end function verify

    // 设置状态
    public function setStatus($arrData,$status,&$strMsg) {
        $arrResult = array();
        switch(true) {
            case isset($arrData['site_id']) :
                $arrResult = $arrData['site_id'];
                break;
            case isset($arrData["isSelectedAll"]):
                $arrResult = $this->getSiteID($arrData);
                break; 
        }
        if(empty($arrResult)) {
            $strMsg = app::get("partner")->_("请选择你要处理的数据!"); return false;
        }

        if(in_array("www",$arrResult)) {
            $strMsg = app::get("partner")->_("www站点不能暂停!");
            return false;
        }

        $objSite = kernel::single("ecaeapi_site");
        foreach($arrResult as $row) {
            if($status) {
                $objSite->active($row);
            } else {    
                $objSite->deactive($row);
            }
        }

        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function setStatus

    public function getSiteID($arrWhere) {
        $arrResult = array();
        $arrTemp   = $this->app->model("site")->getList("*",$arrWhere);
        foreach($arrTemp as $row) {
            $arrResult[] = $row['site_id'];
        }
        return $arrResult;
    } // end function getSiteID


    public function isExist($site_id) {
        return kernel::single("ecaeapi_site")->is_exist($site_id);
    } // end function isExist

    // 获取指定站点的过滤IP设置
    public function getIpFilter($site_id) {
        $arrResult =  kernel::single("ecaeapi_site")->get_blackwhite($site_id);
        if(is_array($arrResult) && !empty($arrResult)) {
            $arrResult['type'] = $arrResult[0];
            $arrResult['ip'] = is_array($arrResult[1])? $arrResult[1] : array($arrResult[1]);
        } else {
            $arrResult = array(
                'type'=>'black',
                'ip'=>array()
            );
        }
        return $arrResult;
    } // end function getIpFilter

    // 指定站点的IP过滤设置
    public function setIpFilter($site_id,$arrData) {
        if(false === ($arrIP = $this->_vertify_ip($arrData,$strMsg))) return array("error" => $strMsg);
        try{
            kernel::single("ecaeapi_site")->set_blackwhite($site_id,$arrData['type'],$arrIP);
            return array("success"=>app::get("partner")->_("设置成功"));
        } catch(Exception $e) {
            return array("error"=>$e->getMessage());
        }
    } // end function setIpFilter

    // 整理IP
    private function _vertify_ip($arrData,&$strMsg = "") {
        $arrResult = array();
        $arrTemp = explode("\n",$arrData['ip']);
        foreach($arrTemp as $row) {    
            $row = trim($row);
            // 不符合ip类型的数据
            if($row) {
                if(!preg_match("/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/",$row)) {
                    $strMsg = $row.app::get("partner")->_("不是合法IP");
                    return false;
                }
                $arrResult[] = $row;
            }
        }
        if("white" == $arrData['type'] && empty($arrResult) ) {
            $strMsg = app::get("partner")->_("白名单不能为空");
            return false;
        }
        return $arrResult;
    } // end function _tiny_ip

    // 获取指定站点的用户信息
    public function getUser($site_id,$tidy = false) {
        $arrTemp = kernel::single("ecaeapi_site")->list_user($site_id);
        $arrResult = array();
        foreach($arrTemp as $row) {
            $arrResult[$row['type']][] = array(
                "user_id"=>$row['user_id'],
                "path"=>$row['path'],
            );
        }
        if(!$tidy) return $arrResult;
        // 数据整理 按提交权限
        $arrData = array();
        foreach($arrResult as $key => $row) {
            $arrTemp = array();
            if($key != "admin") {
                foreach($row as $val) {
                    $arrTemp[] = str_pad($val['user_id'],23).$val['path'];
                }
                $arrData[$key] = implode("\n",$arrTemp);
            } else {
                foreach($row as $val) {
                    $arrTemp[] = $val['user_id'];
                }
                $arrData[$key] = implode("\n",$arrTemp);
            }
        }
        return $arrData;
    } // end function getUser 

    // 设置站点成员    
    public function setUser($site_id,$arrData) {
        if(empty($site_id) || empty($arrData)) return array('error'=>app::get("partner")->_("数据不能为空"));
        $arrUser = array();    
        try{    
            foreach($arrData as $key => $row) {
                $arrUser[$key] = $this->_tidy_user($row,$key,$arrUser['admin']);
            }
        } catch(Exception $e) {
            return array("error"=>$e->getMessage());
        }

        // 建站点创建人不能从管理员中移除
        $creator = kernel::single("ecaeapi_site")->get_attr($site_id,"createuser");
        if($creator) { // 兼容以前的老站点
            if(!in_array($creator[1],$arrUser['admin'])) {
                return array("error"=>$creator[1].app::get("partner")->_("不能从管理员列表中移除"));
            }
        }
        try{
            kernel::single("ecaeapi_site")->add_users($site_id,$arrUser['admin'],$arrUser['committer'],$arrUser['readonly']);
            return array("success"=>app::get("partner")->_("站点成员设置成功"));
        } catch(Exception $e) {
            return array("error"=>$e->getMessage());
        }
    } // function setUser

    private function _tidy_user($arrData,$key,$arrAdmin = array()) {
        $arrResult = array();
        $arrTemp = explode("\n",$arrData);
        $objUser = kernel::single("ecaeapi_user");
        foreach($arrTemp as $row) {    
            if($row = trim($row)) {
                list($user,$path) = preg_split('/\s+/',$row);
                if(!$path) {
                    $path = '/';
                } elseif($path{0}!='/') {
                    $path = '/'.$path;
                }
                if(!$objUser->is_exist($user)) throw new Exception("用户: ".$user." 不存在!");

                if($key != 'admin') {
                    if(!in_array($user,$arrAdmin)) {
                        $arrResult[] = array( $user, $path );
                    }
                } else {
                    $arrResult[] = $user;
                }
            }
        }
        return array_values($arrResult);
    } // end functin _tidy_user

    // 获取指定站点的发布分支列表
    public function getPublishList($site_id) {
        $arrTemp =  kernel::single("ecaeapi_site")->get_published($site_id);
        if(empty($arrTemp)) return false;
        // 如果是静态站点static
        if("static" == $arrTemp[0][0]) return $arrTemp;

        $arrResult = array();
        foreach($arrTemp as $row) {
            $arrResult[] = array(
                'path'=> ("/" == substr($row[0][0],0,1))? $row[0][0] : "/".$row[0][0],
                'ver'=> $row[0][1],
                'cookie'=> $row[1][2],
                'rate'=> ($row[1][3] * 100),
            );
        }
        return $arrResult; 
    } // end function getPublishList

    // 获取指定站点的正在发布的分支列表
    public function getPublishingList($site_id) {
        $arrTemp =  kernel::single("ecaeapi_site")->get_publishing($site_id);
        if(empty($arrTemp)) return false;
        // 如果是静态站点static
        if("static" == $arrTemp[0][0]) return $arrTemp;

        $arrResult = array();
        foreach($arrTemp as $row) {
            $arrResult[] = array(
                'path'=> ("/" == substr($row[0][0],0,1))? $row[0][0] : "/".$row[0][0],
                'ver'=> $row[0][1],
                'cookie'=> $row[1][2],
                'rate'=> ($row[1][3] * 100),
            );
        }
        return $arrResult; 
    } // end function getPublishingList
    
    // 获取指定站点的发布分支列表
    public function getDefaultPublished($site_id) {
        $arrTemp =  kernel::single("ecaeapi_site")->get_default_published($site_id);
        $arrResult = array(
            'path'=> ("/" == substr($arrTemp[0][0],0,1))? $arrTemp[0][0] : "/".$arrTemp[0][0],
            'ver'=> $arrTemp[0][1],
        );
        return $arrResult;
    } // end function getDefaultPublished

    // 路径&&版本号必须唯一
    // 路径正则/^\/[a-z0-9-\/]+$/i 版本号 为int 或 HEAD
    // 访问概率总和为100
    private function _vertify_publish($arrData) {
        if(!isset($arrData['default_publish']) || !isset($arrData['publish']) || empty($arrData['publish']))
             return app::get("partner")->_("数据错误"); 
        $rate = 0;
        $only = array();
        foreach($arrData['publish'] as $row) {
            if(!preg_match("/^\/[a-z0-9-\/]+$/i",$row['path'])) return app::get("partner")->_("路径只能包含a-z,0-9,-,/符号");
            if($row['ver'] != "HEAD") {
                if(!is_numeric($row['ver']) || $row['ver'] <= 0) return app::get("partner")->_("版本只能是HEAD 或为大于0的整数");  
            }
            $key = md5($row['ver']."#".$row['path']);
            if(in_array($key,$only)) return "(".$row['path'].",".$row['ver'].")".app::get("partner")->_("存在相同的发布路径+版本"); 
            $only[] = $key;
            $rate += $row['rate'];
        }
        if($rate != 100) return app::get("partner")->_("访问概率总和必须为100");
        return false;
    }

    // 发布设置
    public function publish($site_id,$arrData){
        if($strMsg = $this->_vertify_publish($arrData)) return array("error"=>$strMsg);

        $arrPublish = array();
        $default = 1;
        $count = 1;
        foreach($arrData['publish'] as $key => &$row){
            $row['path'] = trim($row['path']);
            $row['path'] = ltrim($row['path'],'/');
            $arrPublish[] = array(
                $row['path'],
                trim($row['ver']),
                number_format((intval($row['rate'])/100),2),
            );
            if($arrData['default_publish'] == $key) $default = $count;
            $count++;
        }
        try{
            kernel::single("ecaeapi_site")->publish($site_id,$arrPublish,$default);
            return array("success"=>app::get("partner")->_("设置成功"));
        } catch(Exception $e) {
            return array("error"=>$e->getMessage());
        }
    } // end function publish

    private function _get_default_phpini() {
        return ecae_ini_get_filter();
    }

    // 获取指定站点的php设置
    public function getPhpIni($site_id) { 
        $data = kernel::single('ecaeapi_site')->get_attr($site_id,'phpini');
        $arrData = explode(';',$data);
        $arrResult = $this->_get_default_phpini();
        $arrTemp = array();
        if(is_array($arrData)){
            foreach($arrData as $val){
                list($k,$v) = explode('=',$val);
                $arrTemp[$k] = $v;
            }
        }    
        $default_phpini = ini_get_all();
        foreach($arrResult as $key => $val){
            if(isset($arrTemp[$key]))
                $arrResult[$key]['default'] = $arrTemp[$key];
            elseif(isset($default_phpini[$key])){
                if(!$default_phpini[$key]['local_value']) $default_phpini[$key]['local_value']='Off';
                if(is_numeric($default_phpini[$key]['local_value'])){
                    if($default_phpini[$key]['local_value']==1) $default_phpini[$key]['local_value'] = 'On';
                }
                $arrResult[$key]['default'] = $default_phpini[$key]['local_value'];
            }
        }
        return $arrResult;
    } // end function getPhpIni

    // 提交php设置
    public function setPhpIni($site_id,$arrData) {
        $arrDefaultPhpIni = $this->_get_default_phpini();
        $arrResult = array();
        foreach($arrData as $key => $val){
            if(!isset($arrDefaultPhpIni[$key])) continue;
            if(!in_array($val,$arrDefaultPhpIni[$key]['val'])) continue;
            $arrResult[] = $key.'='.$val;
        }
        try{
            kernel::single("ecaeapi_site")->set_php($site_id,implode(';',$arrResult));
            return array("success"=>app::get("partner")->_("设置成功"));
        } catch(Exception $e) {
            return array("error"=>$e->getMessage());
        }
    } // end function setPhpIni

    // 获取Nginx相关配置
    public function getNginxConfig($site_id) {
        $objSite =     kernel::single("ecaeapi_site");
        $arrConfig = $objSite->nginx_get_rules($site_id);
        $arrResult = array('rules'=>array(),'expires'=>'1d','proxy_enabled'=>true,"limit"=>50,"site_id"=>$site_id);
        // site的数字ID
        $site_info = $objSite->env($site_id);
        $arrResult['site_id'] = $site_info['ECAE_SITE_ID'];
        foreach ($arrConfig as $config) {
            // nginx rewrite
            if ($config[1] == 'rewrite') {
                $arrResult['rules'] = is_array($config[2]) ? $config[2] : array();
            }
            // varnish
            if ($config[1] == 'normal') {
                foreach ($config[2] as $item) {
                    if ($item['id'] == $arrResult["site_id"]."_expires") {
                        $arrResult['expires'] = $item['args'];
                    }
                    if ($item['id'] == $arrResult["site_id"]."_proxy") {
                        $arrResult['proxy_enabled'] = ($item['args'] == 'never;') ? false : true;
                    }
                }
            }
            // nginx rewrite rules limit
            $limit = intval($objSite->get_attr($site_id,'limit.rewrite'));
            $arrResult['limit'] = ($limit)? $limit : $arrResult['limit']; 
        }
        return $arrResult;
    } // end function getNginxConfig

    public function getNginxConstant() {
        $arrResult['options'] = array(
            '' => 'null',
            'last' => 'last',
            'break' => 'break',
            'redirect' => 'redirect',
            'permanent' => 'permanent'
        );
        $arrResult['expires_option'] = array(
            '1d' => '1day',
            '3d' => '3days',
            '1w' => '1week',
            '1m' => '1month',
            '1y' => '1year',
            'max' => 'Max',
        );
        return $arrResult;
    } // end function _nginx_constant

    // 设置Nginx配置
    public function setNginxConfig($site_id,$arrConfig) {
        $arrConfig["proxy_enabled"] = ($arrConfig['proxy_enabled'] == 'on');
        $arrOldConfig = $this->getNginxConfig($site_id);
        $arrConstant = $this->getNginxConstant();
        $id = intval($arrOldConfig['site_id']); // 数字ID
        $arrResult = array('rewrite' => array(),'normal' => array() );

        foreach ($arrConfig["rewrite"] as $key => $row) {
            array_walk($row, 'trim');
            $row['order'] = $key + 1;
            $row['id'] = "{$id}_{$row['order']}";
            $row['site'] = $id;
            $arrResult['rewrite'][] = $row;
        }
        if ($arrConfig["expires"]) {
            if (! array_key_exists($arrConfig["expires"], $arrConstant['expires_option'])) {
                $arrConfig["expires"] = 'max';
            }
            $arrResult['normal'][] = array(
                'id' => "{$id}_expires",
                'site' => $id,
                'key' => 'location ~*',
                'value' => '"\.(ico|css|js|jpe?g|png|gif)$"',
                'args' => "{ expires ".$arrConfig["expires"]."; }",
            );
        }
        $arrResult['normal'][] = array(
            'id' => "{$id}_proxy",
            'site' => $id,
            'key' => 'add_header',
            'value' => 'X-UseReverse-Proxy',
            'args' => ($arrConfig['proxy_enabled'])? 'yes;' : 'never;',
        );

        $config_changed = false;
        if (count($arrOldConfig['rewrite']) != count($arrResult['rewrite'])) $config_changed = true;
        if (empty($arrResult['rewrite']) && empty($arrOldConfig['rewrite'])) $config_changed = false;
        if ($arrResult['rewrite'] && $arrOldConfig['rewrite'] && !$config_changed) {
            foreach ($arrOldConfig as $key => $row) {
                if (array_diff($row, $arrResult['rewrite'][$key])) {
                    $config_changed = true; break;
                }
            }
        }
        if ($arrOldConfig['expires'] != $arrConfig['expires']) $config_changed = true;
        if ($arrOldConfig["proxy_enabled"] !== $arrConfig['proxy_enabled']) $config_changed = true;

        if ($config_changed) {
            try{
                if($result = kernel::single("ecaeapi_site")->nginx_save_rules($site_id, $arrResult)) {
                    return array("success"=>app::get("partner")->_("设置成功"));
                } else {
                    return array("error"=>app::get("partner")->_("设置失败")); 
                }
            } catch(Exception $e) {
                return array("error"=>$e->getMessage());
            }
        } else {
            return array('success' => '设置没有更新过');
        }
    } // end function setNginxConfig

    // 获取指定代理商admin用户的paas站点列表
    public function getAdminSiteList()  {
        // $arrTemp = $this->app->model("site")->getList("*",array("agent"=>$agent,"user"=>"admin","type"=>"paas"));
        $arrTemp = $this->app->model("site")->getList("*",array("user"=>"admin",'type'=>'paas'));
        $arrResult = array();
        // 暂时这里过滤paas站点 具体要在erlang代码里处理
        foreach($arrTemp as $row) {
            // paas站点且不是www站点
            if($row['type'] == "paas" && substr($row['domain'],0,4) != 'www.') {
                $arrResult[] = array(
                    "id"     => $row['site_id'],
                    "name"   => ($row["name"])? $row["name"] : $row["id"],
                    "domain" => $row["domain"]
                );
            }
        }
        return $arrResult;
    } // end function getAdminSiteList

    // 获取指定站点的全局变量
    public function getEnv($site_id,$tidy = false) {
        $arrData = kernel::single("ecaeapi_site")->env($site_id);
        if(!$tidy) return $arrData;

        // 整理常量
        $arrTemp = array();
        foreach($arrData as $key=>$row) {
            $arrT = explode("_",$key);
            $arrTemp[strtolower($arrT[1])][$key] = $row;
        }
        $arrResult = array();
        foreach($arrTemp as $key=>$row) {
            if(count($row) == 1) {
                foreach($row as $k=>$r) $arrResult['other'][$k] = $r; 
            } else {
                $arrResult[$key] = $row;
            }
        }
        return $arrResult;
    } // end function getEnv

    public function delete($arrData,&$strMsg) {
        if(!isset($arrData['site_id']) || count($arrData['site_id']) != 1) {
            $strMsg = app::get("partner")->_("一次处理最多删除一个站点!"); return false;
        }
        if(in_array("www",$arrData['site_id'])) {
            $strMsg = app::get("partner")->_("不能删除自己!"); return false;
        }

        $objSite = kernel::single("ecaeapi_site");
        foreach($arrData['site_id'] as $row) {
            try {
                $objSite->delete($row,null,2);
            } catch(Exception $e) {
                $strMsg = $e->getMessage();
                return false;
            }
        }
        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function delete

    public function setDeadLine($site_id,$arrData) {
        if(!isset($arrData['period']) ) return array("error" => "推迟周期不能为空");
        $period = intval($arrData['period']);
        if($period <=0 ) return array("error" => "推迟周期不能小于等于0");

        $arrSite = $this->app->model('site')->dump($site_id);
        if(empty($arrSite)) return array("error" => "错误的站点ID"); 

        $arrResult = array(
            array("period",   ($arrSite['period'])? $arrSite['period'] + $period :  $period),
            array("deadline", ($arrSite['deadline'])? strtotime("+".$period." month",$arrSite['deadline']) : strtotime("+".$period." month",time()))
        ); 
        try {
            if(kernel::single("ecaeapi_site")->set_info($site_id,$arrResult)) {
                return array("success" => app::get("partner")->_("设置成功"));
            } else {
                return array("error" => app::get("partner")->_("设置失败"));
            }
        } catch(Exception $e) {
            return array("error" => $e->getMessage());
        }
    } // end function setDeadLine

    // 更新站点varnish
    public function clearVarnish($arrData,&$strMsg) {
        $arrResult = array();
        switch(true) {
            case isset($arrData['site_id']) :
                $arrResult = $arrData['site_id'];
                break;
            case isset($arrData["isSelectedAll"]):
                $arrResult = $this->getSiteID($arrData);
                break; 
        }
        if(empty($arrResult)) {
            $strMsg = app::get("partner")->_("请选择你要处理的数据!"); return false;
        }

        $objSite = kernel::single("ecaeapi_site");
        foreach($arrResult as $row) {
            try {
                $objSite->purge_varnish($row);
            } catch(Exception $e) {
                $strMsg = $e->getMessage();
                return false;
            }
        }
        $strMsg = app::get("partner")->_("操作成功");
        return true;
    } // end function clearVarnish

} // end class 
