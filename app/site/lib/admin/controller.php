<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_admin_controller extends desktop_controller 
{

    /*
     * @param object $app
     */
    function __construct($app) 
    {
        parent::__construct($app);
        $this->_request = kernel::single('base_component_request');
        $this->_response = kernel::single('base_component_response');
    }//End Function

    /*
     * 错误
     * @param string $msg
     */
    public function _error($msg='非法操作') 
    {
		header("Content-type: text/html; charset=utf-8");
        //die($msg);
		echo $msg;exit;
        //方法待定
    }//End Function
	
	protected function check($theme,&$msg='') 
    {
        if(empty($theme)){
			$msg = app::get('site')->_('缺少参数');
			return false;
		}
		/** 权限校验 **/
		if($theme && preg_match('/(\..\/){1,}/', $theme)){
			$msg = app::get('site')->_('非法操作');
			return false;
		}
        $dir = THEME_DIR . '/' . $theme;
		if (!is_dir($dir)){
			$msg = app::get('site')->_('路径不存在');
			return false;
		}
        return true;
    }//End Function

    /*
     * 跳转
     * @param string $url
     */
    public function _redirect($url) 
    {
        $this->_response->set_redirect($url)->send_headers();
    }//End Function


}//End Class
