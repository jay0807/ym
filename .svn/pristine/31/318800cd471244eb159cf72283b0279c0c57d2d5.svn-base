<?php
class yunmall_oauth_taobao {

    public $appkey    = "21385852";
    public $appsecret = "5794ab86898f0b78ce179a7d856c2f22";
    public $auth_url  = "https://oauth.taobao.com/authorize";
    public $token_url = "https://oauth.taobao.com/token";
    public $quit_url  = "https://oauth.taobao.com/logoff";

    public function __construct() {
		$this->http = new base_httpclient();
        $this->appkey    = defined("TAOBAO_APPKEY")? constant("TAOBAO_APPKEY") : $this->appkey; 
        $this->appsecret = defined("TAOBAO_APPSECRET")? constant("TAOBAO_APPSECRET") : $this->appsecret;
    } // end function __construct

    public function createAuthURL($state,$redirect){			
		return 	$this->auth_url."?response_type=code&client_id=$this->appkey&redirect_uri=$redirect&state=$state";
    } // end function createAuthCodeURL

	public function connect($code,$state,$redirect){
        $query = array(
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->appkey,
            'client_secret' => $this->appsecret,
            'code'          => $code,
            //'state'=>$state,
            'redirect_uri'  => $redirect
        );
        $response = $this->http->post($this->token_url,$query);
	    return json_decode($response,1);
    } // end function connect

    public function refreshToken($token) {
        $query = array(
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->appkey,
            'client_secret' => $this->appsecret,
            'refresh_token' => $token,
        );
	    $response = $this->http->post($this->token_url,$query);
	    return json_decode($response,1); 
    } // end function refresh

    public function logoutUrl($redirect) {
        return $this->quit_url."?client_id=".$this->appkey."&redirect_uri=".$this->redirect."&view=web"; 
    } // end function logout

} // end class
