<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */


class site_ctl_admin_theme_manage extends site_admin_controller
{

    /*
     * workground
     * @var string
     */
    var $workground = 'site.wrokground.theme';

    //列表
    public function index()
    {
        //finder
        kernel::single('site_theme_install')->check_install();
        $actions = array(

                    array('label'=>app::get('site')->_('上传模板'),'href'=>'index.php?app=site&ctl=admin_theme_manage&act=swf_upload','target'=>'dialog::{title:\''.app::get('site')->_('上传模板').'\',width:400,height:280}'),
                    array('label'=>app::get('site')->_('在线安装模板'),'href'=>'http://addons.shopex.cn/templates/new/#'.kernel::router()->app->base_url(1) . '?app=site&ctl=admin_theme_manage&act=install_online','target'=>'_blank'),

                    array('label'=>app::get('site')->_('删除'),'icon'=>'del.gif','confirm'=>app::get('site')->_('确定删除选中项？删除后不可从回收站恢复'),'submit'=>'?app=site&ctl=admin_theme_manage&act=delete')
                );

        $this->finder('site_mdl_themes',array('title'=>app::get('site')->_('模板管理'), 'actions'=>$actions,'use_buildin_recycle'=>false));

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
        return true;
    }//End Function

    //flash上传
    public function swf_upload()
    {
        $this->pagedata['ssid'] = kernel::single('base_session')->sess_id();
        $this->pagedata['swf_loc'] = kernel::router()->app->res_url;
        $this->pagedata['upload_max_filesize'] = kernel::single('site_theme_install')->ini_get_size('upload_max_filesize');
        $this->display('admin/theme/manage/swf_upload.html');
    }//End Function

    public function install_online()
    {
        $params = $this->_request->get_post();
        if(isset($params['url']) && isset($params['tpl_name']) && isset($params['fullsize'])){
            $params['name'] = ($params['tpl_name']) ? $params['tpl_name'] : basename($params['url']);       //如果没有传入文件名，则使用basename
            $downObj = kernel::single('site_utility_download');
            $ident = $downObj->set_task($params);
            $this->pagedata['ident'] = $ident;
            $this->pagedata['success_url'] = 'index.php?app=site&ctl=admin_theme_manage&act=install';
            $this->singlepage('admin/download/process.html');
        }
    }//End Function

    public function upload()
    {
        $themeInstallObj = kernel::single('site_theme_install');
        $res = $themeInstallObj->install($_FILES['Filedata'],$msg);
        if($res){
			$theme_url = defined('THEMES_IMG_URL') ? THEMES_IMG_URL : kernel::base_url(1) . '/themes';
            $img = $theme_url.'/' . $res['theme'] . '/preview.jpg';
            echo '<img src="'.$img.'" onload="$(this).zoomImg(50,50);" />';
        }else{
            echo $msg;
        }
    }//End Function

    public function install()
    {
        $ident = $this->_request->get_get('ident');
        $downObj = kernel::single('site_utility_download');
        $task_info = $downObj->get_task($ident);
        if(empty($task_info))   $this->_error();
        $file = $downObj->get_work_dir() . '/' . $ident . '/' . $task_info['name'];

        $msg =app::get('site')->_('无法找到安装文件');

        if(is_file($file)){
            $fileInfo['tmp_name'] = $file;
            $fileInfo['name'] = time();
            $fileInfo['error'] = '0';
            $fileInfo['size'] = filesize($file);
            $themeInstallObj = kernel::single('site_theme_install');
            $res = $themeInstallObj->install($fileInfo, $msg);
        }
        if($res){
			$theme_url = defined('THEMES_IMG_URL') ? THEMES_IMG_URL : kernel::base_url(1) . '/themes';
            $img = $theme_url . '/' . $res['theme'] . '/preview.jpg';
            $this->pagedata['img'] = '<img src="'.$img.'" />';
            $this->pagedata['msg'] = app::get('site')->_('模板安装成功，您可以在模板列表中启用它。');
        }else{
            $this->pagedata['msg'] = $msg;
        }
        $this->singlepage('admin/download/result.html');
    }//End Function

    public function set_default()
    {
        $this->begin('javascript:finderGroup["'.$_GET['finder_id'].'"].refresh();');
        $theme = $this->_request->get_get('theme');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        if($theme){
            if(kernel::single('site_theme_base')->set_default($theme)){
                $this->end(true, app::get('site')->_('设置成功'));
            }else{
                $this->end(false, app::get('site')->_('设置失败'));
            }
        }
    }//End Function

    public function set_style()
    {
        $this->begin();
        $theme = $this->_request->get_get('theme');
        $style_id = $this->_request->get_get('style_id');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        if($theme){
            $styles = kernel::single('site_theme_base')->get_theme_styles($theme);
            if(is_array($styles) && array_key_exists($style_id, $styles)){
                if(kernel::single('site_theme_base')->set_theme_style($theme, $styles[$style_id]))
                    $this->end(true, app::get('site')->_('设置成功'), 'index.php?app=site&ctl=admin_theme_manage');
            }
            $this->end(false, app::get('site')->_('设置失败'));
        }
    }//End Function

    public function bak() {
        $this->begin();
        $theme = $this->_request->get_get('theme');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        $data = kernel::single('site_theme_tmpl')->make_configfile($theme);

        if(file_put_contents(THEME_DIR . '/' . $theme . '/theme_bak.xml', $data)) {
            $this->end(true, app::get('site')->_('备份成功！'));
        } else {
            $this->end(false, app::get('site')->_('备份失败！'));
        }
    }

    public function reset() {
        $this->begin();
        $theme = $this->_request->get_get('theme');
        $loadxml = $this->_request->get_get('rid');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        if(kernel::single("site_theme_install")->init_theme($theme, true, false, $loadxml)) {
            $this->end(true, app::get('site')->_('还原成功！'));
        } else {
            $this->end(false, app::get('site')->_('还原失败！'));
        }
    }

    public function delete()
    {
        $this->begin();
        $post = $this->_request->get_post();
		foreach ((array)$post['theme'] as $theme){
			if(!$this->check($theme,$msg))   $this->_error($msg);
		}
        if(app::get('site')->model('themes')->delete_file(array('theme'=>$post['theme']))){
            $this->end(true, app::get('site')->_('删除成功'), 'javascript:finderGroup["'.$_GET['finder_id'].'"].unselectAll();finderGroup["'.$_GET['finder_id'].'"].refresh();');
        }else{
            $this->end(false, app::get('site')->_('删除失败'));
        }
    }//End Function

    public function download()
    {
        $theme = $this->_request->get_get('theme');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        kernel::single('site_theme_tmpl')->output_pkg($theme);
        exit;
    }//End Function

    public function cache_version()
    {
        $theme = $this->_request->get_get('theme');
		if(!$this->check($theme,$msg))   $this->_error($msg);
        $this->begin();
        $this->end(kernel::single('site_theme_tmpl')->touch_theme_tmpl($theme));
    }//End Function

    public function maintenance()
    {
        if(is_dir(THEME_DIR)){
            set_time_limit(0);
            cachemgr::init(false);
            header('Content-type: text/html;charset=utf-8');
            ignore_user_abort(false);
            ob_implicit_flush(1);
            ini_set('implicit_flush',true);
            kernel::$console_output = true;
            while(ob_get_level()){
                ob_end_flush();
            }
            echo str_repeat("\0",1024);
            $dir = new DirectoryIterator(THEME_DIR);
            echo '<pre>';
            echo '>update themes'."\n";
            foreach($dir as $file)
            {
                $filename = $file->getFilename();
                if($filename{0}=='.'){
                    continue;
                }else{
                    kernel::single('site_theme_base')->update_theme_widgets($filename);
                }
            }
            echo 'ok.</pre>';
        }
    }//End Function

}//End Class
