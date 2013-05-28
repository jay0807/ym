<?php
class yunmall_openapi_passport extends yunmall_openapi {

    public function login() {
        //  判断用户是否存在
        $state = isset($_GET["state"])? $_GET["state"] : null;
        if(!$state) {
            //die("illegal access");
            $this->url();exit;
        }   

        $error = isset($_GET["error"])? $_GET["error"] : null;
        if($error) die($error);

        $code = isset($_GET["code"])? $_GET["code"] : null;
        if(!$code) die("illegal access");

        if($state == "create") {
            // 创建用户或用户登陆
            kernel::single("yunmall_taobao")->create($code,$state);
        } elseif(substr($state,0,3) == "web") {
            $arrTemp = explode("_",$state);  
            if(isset($arrTemp[1]) && $url = base64_decode($arrTemp[1])) {
                $url = str_replace("index.php","",$url)."oauth.php";
                header("location:".$url."?code=".$code."&state=".$state);
            } else {
                die("illegal access");
            }
        } elseif($state == "login") {
            kernel::single("yunmall_taobao")->login($code,$state);
        }else {
            die("illegal access");
        }
    } // end function login

    public function url() {
        $state = isset($_GET["state"])? $_GET["state"] : "create";  
        header("location:".kernel::single("yunmall_oauth_taobao")->createAuthURL($state,kernel::openapi_url("openapi.passport","login")));  
    } // end function url

} // end class
