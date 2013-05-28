<?php
class yunmall_taoapi {
    
    private $tc  = null;
    private $dir = null;
    private $session = null;
    private $app = null;

    public function __construct($app) {
        $this->app = $app;
        $this->dir = $app->app_dir."/taoapi/top/";
        require_once($this->dir."../lotusphp_runtime/ObjectUtil/ObjectUtil.php");
        require_once($this->dir."../lotusphp_runtime/Config.php");
        require_once($this->dir."../TopSdk.php");
        require_once($this->dir."TopClient.php");
        require_once($this->dir."RequestCheckUtil.php");

        $this->tc = new TopClient;         
        $this->tc->appkey    = constant("TAOBAO_APPKEY");
        $this->tc->secretKey = constant("TAOBAO_APPSECRET");
        $this->tc->format    = "json";
    } // end function __construct

    private function _include_api($api) {
        require_once($this->dir."request/".$api.".php"); 
        return new $api;
    } // end function include

    public function setSession($session) {
        $this->session = $session; 
    } // end function setSession

    private function _request($req) {
        return $this->tc->execute($req,$this->session);
    } // end function _request
    
    public function getBuyerInfo($fields = "user_id,nick,sex,buyer_credit,avatar,has_shop,vip_info") {
        $req = $this->_include_api("UserBuyerGetRequest");  
        $req->setFields($fields);
        
        return $this->_request($req);
    } // end function getBuyerInfo

    public function getItemList($nick_name,$page_no = 1,$page_size = 40,$fields = "product_id,tsc,cat_name,name") {
        $req = $this->_include_api("ProductsGetRequest");  

        $req->setFields($fields);
        $req->setNick($nick_name);
        $req->setPageNo($page_no);
        $req->setPageSize($page_size);

        return $this->_request($req);
    } // end function getProductsList

    public function getItemInfo($item_id,$fields="skus,num,price,title,num_iid") {
        $req = $this->_include_api("ItemGetRequest");
        $req->setFields($fields);
        $req->setNumIid($item_id);

        return $this->_request($req);
    } // end function getItemInfo

    public function getItemSku($item_id,$fields="sku_spec_id,sku_id,properties,quantity,price,status,properties_name") {
        $req = $this->_include_api("ItemSkusGetRequest");
        $req->setFields($fields);
        $req->setNumIids($item_id);
        return $this->_request($req);
    } // end function getItemSku

    public function getVasSubscribe($nick,$token) {
        $req = $this->_include_api("VasSubscribeGetRequest");
        $req->setNick($nick);
        $req->setArticleCode($token);
        $req->setNumIids($item_id);
        return $this->_request($req);
    } // end function getVasSubscribe

    public function initJSSDK() {
        $app_key   = constant("TAOBAO_APPKEY");/*填写appkey */
        $secret    = constant("TAOBAO_APPSECRET");/*填入Appsecret'*/
        $timestamp = time()."000";
        $message   = $secret.'app_key'.$app_key.'timestamp'.$timestamp.$secret;
        $mysign    = strtoupper(hash_hmac("md5",$message,$secret));
        setcookie("timestamp",$timestamp);
        setcookie("sign",$mysign);
    } // end function initJSSDK
    
} // end class
