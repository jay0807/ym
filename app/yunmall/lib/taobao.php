<?php
class yunmall_taobao {

    private $app = null;
    public function __construct($app) {
        $this->app = $app;
        kernel::single('base_session')->start();

        $this->user_id = isset($_SESSION["user"]["id"])? $_SESSION["user"]["id"] : null;
    } // end function __construct

    private function _test_data() {
        return array(
              "w2_expires_in"    => 1800,
              "taobao_user_id"   => "76388154",
              "taobao_user_nick" => "bluefrog",
              "w1_expires_in"    => 86400,
              "re_expires_in"    => 2592000,
              "r2_expires_in"    => 86400,
              "expires_in"       => 86400,
              "token_type"       => "Bearer",
              "refresh_token"    => "6202b284e5e62fc9db6f696c7eafbff6b365ZZc586c312a76388153",
              "access_token"     => "620272824529cf9d50c9b5fff4ab8d195e8fhjded293b1d76388153",
              "r1_expires_in"    => 86400
        );
    } // end function _test_data

    private function _get_auth($code,$state) {
        // 取出oauth信息
        $arrAuth = kernel::single("yunmall_oauth_taobao")->connect($code,$state,constant("YM_REDIRECT_URI"));
        if(!isset($arrAuth["access_token"])) {
            die("access_token got failed");
        }
        return $arrAuth;
    } // end function _get_auth

    public function create($code,$state = "") { 
        $arrAuth = $this->_get_auth($code,$state);
        //$arrAuth = $this->_test_data();
        // 判断是否存在表里
        if($this->_checkUser($arrAuth)) { 
        } else { // 注册操作
            $this->_register($arrAuth);
        }
        // 登陆操作
        $this->_login($arrAuth);
    } // end function login

    public function login($code,$state = "") {
        $arrAuth = $this->_get_auth($code,$state);
        //$arrAuth = $this->_test_data();
        // 判断是否存在表里
        if($this->_checkUser($arrAuth)) { 
            $this->_login($arrAuth);
        } else { // 注册操作
            die("no site user");
        }
    } // end function login

    private function _register($arrAuth) {
        $objUser = $this->app->model("user");
        $arrData = array(
            "taobao_id"     => $arrAuth["taobao_user_id"], 
            "taobao_nick"   => urldecode($arrAuth["taobao_user_nick"]),
            "create_time"   => time(),
            "create_ip"     => base_request::get_remote_addr(),
            "access_expire" => time() + $arrAuth["expires_in"],
            "access_token"  => $arrAuth["access_token"],
            "refresh_expire" => time() + $arrAuth["re_expires_in"],
            "refresh_token"  => $arrAuth["access_token"],
        );
        if($objUser->save($arrData)) {
            return $arrData;
        } else {
            die("register failded,please try again");
        }
    } // end function _register

    private function _login($arrAuth) {
        $objUser = $this->app->model("user");
        $arrData = array(
            //"taobao_id"     => $arrAuth["taobao_user_id"], 
            //"taobao_nick"   => $arrAuth["taobao_user_nick"],
            "login_time"   => time(),
            "login_ip"     => base_request::get_remote_addr(),
            //"access_expire" => time() + $arrAuth["expires_in"],
            //"access_token"  => $arrAuth["access_token"],
            //"refresh_expire" => time() + $arrAuth["re_expires_in"],
            //"refresh_token"  => $arrAuth["access_token"],
        );
        if(!isset($arrAuth["taobao_user_id"])) return false;
        if($objUser->update($arrData,array("taobao_id"=>$arrAuth["taobao_user_id"]))) {
            $arrData = $objUser->dump(array("taobao_id"=>$arrAuth["taobao_user_id"]));  
            $_SESSION["auth"] = $arrAuth;
            $_SESSION["user"] = $arrData;
            setcookie("nickname",$arrData["taobao_nick"],0,"/");
            //echo kernel::single("yunmall_controller")->gen_url(array('app'=>'yunmall', 'ctl'=>'site_usercenter'));exit;
            header("location:".kernel::single("yunmall_controller")->gen_url(array('app'=>'yunmall', 'ctl'=>'site_usercenter')));
        } else {
            die("login failded,please try again");
        }
    } // end function _login

    private function _checkUser($arrAuth) {
        $objUser = $this->app->model("user");
        $arrData = $objUser->dump(array("taobao_id"=>$arrAuth["taobao_user_id"]));
        return $arrData;
    } // end function _checkCreate

    public function getCheckCode() {
        $checkcode = md5(time().rand(0,99999));
        $_SESSION["checkcode"] = $checkcode;
        return $checkcode;
    } // end function getCheckCode   

    public function verifyCheckCode($checkcode) {
        return $_SESSION["checkcode"] == $checkcode;
    } // end function verifyCheckCode

    public function save($arrData,&$strMsg = "") {
        if($strMsg = $this->verify($arrData)) return false;

        $arrUser = array(
            "ent_name" => trim($arrData["ent_name"]),
            "recordno" => trim($arrData["recordno"]),
            "contact"  => trim($arrData["contact"]),
            "phone"    => trim($arrData["phone"]),
            "email"    => trim($arrData["email"]),
        );
        // 没有创建相关站点
        if(empty($_SESSION["user"]["site"]) && $arrData["ent_url"]) {
            $arrUser["ent_url"] = trim($arrData["ent_url"]);
            // 这里要拉一下 vas的信息
            if($site = $this->createSite($arrData["ent_url"],$strMsg)) {
                $arrUser["site"] = $site;
            } else {
                return false;
            }
        } else {
            $site = $_SESSION["user"]["site"]; 
        }
        $this->initApp($site,$arrUser);

        $objUser = $this->app->model("user");

        if($objUser->update($arrUser,array("id"=>$this->user_id))) {
            $_SESSION["user"] = $objUser->dump(array("id"=>$this->user_id));
            $strMsg = "保存成功";
            return true;
        } else {
            $strMsg = "保存失败";
            return false;
        }
    } // end function save

    public function createSite($url,&$strMsg) {
        if(preg_match_all("/(http:\/\/]){0,1}([a-z0-9-]{1,})\.(taobao\.com|tmall\.com){1,1}/isU",trim($url),$arrData)) {
            $domain = $arrData[2][0];
            if("www" == $domain) {
                $strMsg = "呵呵呵"; 
                return false;
            }
            $objUser = $this->app->model("user");
            if($objUser->dump(array("site"=>$domain))) { // 已存在
                $strMsg = "域名已存在,请联系客服";
                return false;
            } else {
                $package = $this->app->getConf("ecae.package");
                $app = $this->app->getConf("ecae.app");
                if($package && $app) {
                    try {
                        kernel::single("ecaeapi_site")->add("admin",$domain,$package,$app);  
                        $strMsg = "创建站点成功";
                        return $domain;
                    } catch(Exception $e) {
                        $strMsg = $e->getMessage();
                        return false;
                    }
                } else {
                    $strMsg = "系统错误";
                    return false;
                }
            } 
        } else {
            $strMsg = "非法商城URL"; 
            return false;
        }
    } // end function createSite

    public function verify($arrData) {
        if(!$arrData) return "请填写表单";
        if(!$this->verifyCheckCode($arrData["checkcode"])) return "非法操作";
        if(!$arrData["ent_name"]) return "请填写企业名称";
        if(!$_SESSION["user"]["site"]) {
            if(!$arrData["ent_url"]) return "请填写商城URL";
            if(!preg_match("/(http:\/\/]){0,1}([a-z0-9-]{1,})\.(taobao\.com|tmall\.com){1,1}/isU",trim($arrData["ent_url"]))) return "请填写正确的商城url";
        }
        if(!$arrData["recordno"]) return "请填写网站备案号";
        if(!$arrData["contact"]) return "请填写联系人姓名";
        if(!$arrData["phone"]) return "请填写手机号";
        if(!preg_match("/[0-9+-]{1,}/isU",$arrData["phone"])) return "请填写正确的手机号";
        if(!$arrData["email"]) return "请填写邮箱";
        if(!preg_match("/[a-z0-9_\.-]{1,}@[a-z0-9_\.-]{1,}/isU",$arrData["email"])) return "请填写正确的邮箱";
        
        return false;
    } // end function verify 


    public function update($arrData,&$strMsg = "") {
        $arrUser = array(
            "ent_name" => trim($arrData["ent_name"]),
            "recordno" => trim($arrData["recordno"]),
            "contact"  => trim($arrData["contact"]),
            "phone"    => trim($arrData["phone"]),
            "email"    => trim($arrData["email"]),
            "ent_url"  => trim($arrData["ent_url"]),
            "site"     => trim($arrData["site"]),
        );

        $objUser = $this->app->model("user");

        if($objUser->update($arrUser,array("id"=>$arrData["id"]))) {

            if($arrUser["site"]) {
                $this->initApp($arrUser["site"],$arrUser);
            }
            $strMsg = "保存成功";
            return true;
        } else {
            $strMsg = "保存失败";
            return false;
        }
    } // end function update

    public function initApp($domain,$arrData) {
        $api_url = "http://".$domain.".cloudmall.shopex.cn/api.php";
        $objApp  = kernel::single("yunmall_app");
        $objApp->setUrl($api_url);
        if(isset($arrData["ent_name"])) {
            $objApp->set("name",$arrData["ent_name"]);
        }
        if(isset($arrData["recordno"])) {
            $objApp->set("recordno",$arrData["recordno"]); 
        }
        if(isset($arrData["ent_url"])) {
            if(preg_match("/(http:\/\/]){0,1}([a-z0-9-]{1,})\.(taobao\.com|tmall\.com){1,1}/isU",$arrData["ent_url"],$arrTemp)) {
                $objApp->set("url","http://".$arrTemp[0]); 
            }
        }
    } // end function initApp

} // end class
