<?php
class yunmall_ctl_site_usercenter extends site_controller {

    private $auth = null;
    private $user = null;
    public function __construct($app) {
        parent::__construct($app);
        kernel::single('base_session')->start();
        if(isset($_SESSION["auth"]) && $_SESSION["auth"] && isset($_SESSION["user"]) && $_SESSION["user"] ) {
            //$_SESSION["user"]["site"] = "";
            //$_SESSION["user"]["site"] = ($_SESSION["user"]["site"])? "http://".$_SESSION["user"]["site"].".cloudmall.shopex.cn" : $_SESSION["user"]["site"];
            $this->auth = $_SESSION["auth"]; 
            $this->user = $_SESSION["user"];
        }
    } // end function __construct

    private function _check() {
        if($this->auth && $this->user) {
        } else {
            header("location:".$this->gen_url(array('app'=>'site', 'ctl'=>'default')));exit; 
        }
    } // end function _check

    public function index() {
        $this->_check();

        $this->pagedata["user"] = $this->user;
        $this->pagedata["auth"] = $this->auth;
        $this->pagedata["checkcode"] = kernel::single("yunmall_taobao")->getCheckCode();
        $this->page("site/usercenter/info.html");
    } // end function index

    public function save() {
        $this->_check();
        $this->begin($this->gen_url(array('app'=>'yunmall','ctl'=>'site_usercenter','act'=>'index')));
        $this->end(kernel::single("yunmall_taobao")->save($_POST["data"],$strMsg),$strMsg);
    } // end function save

    public function login() {
        if($this->auth && $this->user) {
            header("location:".$this->gen_url(array('app'=>'yunmall', 'ctl'=>'site_usercenter')));exit; 
        } else {
            //echo kernel::single("yunmall_oauth_taobao")->createAuthURL("login",kernel::openapi_url("openapi.passport","login"));exit;
            header("location:".kernel::single("yunmall_oauth_taobao")->createAuthURL("login",kernel::openapi_url("openapi.passport","login")));exit; 
        }
    } // end function login

} // end class
