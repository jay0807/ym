<?php
class yunmall_app {

    private $app = null;
    private $http = null;
    private $api_url = null;
    public function __construct($app) {
        $this->http = new base_httpclient(); 
    } // end function __construct

    public $error = null;

    public function setUrl($url) {
        $this->api_url = $url;
    } // end function setUrl;

    public function api($arrData) {
        $arrData["sign"] = $this->sign($arrData);
        //var_dump($arrData);
        //var_dump($this->api_url);
        $res = $this->http->post($this->api_url,$arrData);
        //var_dump($res);exit;
        if(!($arrData = json_decode($res,1))) return false; 
        if(isset($arrData["error"])) {
            $this->error = $arrData["error"];
            return false;
        } else {
            if(isset($arrData["data"])) return $arrData["data"];
            return true;
        }
    } // end function api

    public function set($key,$value) {
        $arrData = array(
            "method" => "setting.set",
            "key"    => $key,
            "value"  => $value,
        );
        return $this->api($arrData); 
    } // end function set

    public function get($key) {
        $arrData = array(
            "method" => "setting.get",
            "key"    => $key,
        );
        return $this->api($arrData);
    } // end function get

    public function sign($arrData,$token = "" ) {
        if(isset($arrData["sign"])) unset($arrData["sign"]);
        ksort($arrData);
        $str = $this->_sign($arrData);
        return strtolower(md5($str.$token));
    } // end function sign

    private function _sign($arrData) {
        $str = "";
        foreach($arrData as $key => $value) {
            if(is_array($value)) {
                $value = $this->_sign($value);
            }
            $str .= "{$key}($value)";
        }
        return $str;
    } // end function _sign

} // end class
