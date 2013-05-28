<?php
class ecaeapi_auth {
	private $instance = null;
	
	public function __construct($app) {
		$this->app = $app;
		$this->instance = ecaeapi_api::getInstance();
	}

	// shopex授权的资源
    public function getPartList()
    {
        try{
            return $this->instance->goods_auth_list_my_goods('parts');
        } catch (Exception $e) {
            return false;
        }
	}

	// 上级授权的应用
	public function getAppList()
    {
        try{
            return $this->instance->goods_auth_list_my_goods('apps');
        } catch (Exception $e) {
            return false;
        }
	}

} // end class
