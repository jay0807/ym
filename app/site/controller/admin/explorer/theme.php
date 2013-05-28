<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_ctl_admin_explorer_theme extends site_admin_controller 
{
    /*
     * workground
     * @var string
     */
    var $workground = 'site.wrokground.theme';

    private function get_theme_dir($theme, $open_path='') 
    {
        return realpath(THEME_DIR . '/' . $theme . '/' . str_replace(array('-','.'), array('/','/'), $open_path));  
    }//End Function

    /*
     * 目录浏览
     */
    public function directory() 
    {
        $theme = $this->_request->get_get('theme');
        $open_path = $this->_request->get_get('open_path');
		
		/** 加入目录限制 **/
        if(!$this->check($theme,$msg))   $this->_error($msg);
        
        $fileObj = kernel::single('site_explorer_file');
        $dir = $this->get_theme_dir($theme, $open_path);
        $filter=array(
                 'id' => $atheme,
                 'dir' => $dir,
                 'show_bak' => false,
                 'type' => 'all'
             );
        $file = $fileObj->file_list($filter);
        $file = $fileObj->parse_filter($file);
        $this->pagedata['file'] = array_reverse($file);
        $this->pagedata['url'] = sprintf('index.php?app=%s&ctl=%s&act=%s&theme=%s',
            $this->_request->get_get('app'),
            $this->_request->get_get('ctl'),
            $this->_request->get_get('act'), 
            $this->_request->get_get('theme')
        );
        $this->pagedata['theme'] = $theme;
        $this->pagedata['open_path'] = $open_path;
        $this->pagedata['last_path'] = strrpos($open_path, '-') ? substr($open_path, 0, strrpos($open_path, '-')) : ($open_path ? ' ' : '');
		$this->pagedata['finder_id'] = $_GET['finder_id'];
        $this->display('admin/explorer/theme/directory.html');
    }//End Function

    /*
     * 文件详情
     */
    public function detail() 
    {
        $theme = $this->_request->get_get('theme');
        $open_path = $this->_request->get_get('open_path');
        $file_name = $this->_request->get_get('file_name');

        if(!$this->check($theme,$msg))   $this->_error($msg);
        
        $fileObj = kernel::single('site_explorer_file');
        $dir = $this->get_theme_dir($theme, $open_path);
        $filter=array(
                 'id' => $theme,
                 'dir' => $dir,
                 'show_bak' => true,
                 'type' => 'all'
             );
        $filenameInfo = pathinfo($file_name);
        $this->pagedata['file_baklist'] = $fileObj->get_file_baklist($filter, $file_name);
        $this->pagedata['theme'] = $theme;
        $this->pagedata['open_path'] = $open_path;
        $this->pagedata['file_name'] = $file_name;
        if(in_array($filenameInfo['extension'], array('css', 'html', 'js', 'xml'))){
            $this->pagedata['file_content']  = $fileObj->get_file($dir . '/' . $file_name);
            $this->display('admin/explorer/theme/tpl_source.html');
        }else{
            $this->pagedata['file_url'] =  kernel::base_url(1) . rtrim(str_replace('//', '/', '/themes/' . $theme . '/' . str_replace(array('-','.'), array('/','/'), $open_path) . '/' . $file_name));
            $this->display('admin/explorer/theme/tpl_image.html');
        }
    }//End Function

    /*
     * 保存文件
     */
    public function svae_source() 
    {
        $this->begin();
        $theme = $this->_request->get_post('theme');
        $open_path = $this->_request->get_post('open_path');
        $file_name = $this->_request->get_post('file_name');

        if(!$this->check($theme,$msg))   $this->_error($msg);

        $has_bak = ($this->_request->get_post('has_bak')) ? true : false;
        $has_clearcache = ($this->_request->get_post('has_clearcache')) ? true : false;
        $file_source = $this->_request->get_post('file_source');

        $fileObj = kernel::single('site_explorer_file');
        $dir = $this->get_theme_dir($theme, $open_path);
        if($has_bak){
            $fileObj->backup_file($dir . '/' . $file_name);
        }
        $fileObj->save_source($dir . '/' . $file_name, $file_source);
        if($has_clearcache){
            @touch($dir . '/' . $file_name);
            kernel::single('site_theme_base')->set_theme_cache_version($theme);
        }
        $this->end(true, app::get('site')->_('保存成功'));
    }//End Function

    /*
     * 保存图片文件
     */
    public function save_image() 
    {
        $this->begin();
        $theme = $this->_request->get_post('theme');
        $open_path = $this->_request->get_post('open_path');
        $file_name = $this->_request->get_post('file_name');

        if(!$this->check($theme,$msg))   $this->_error($msg);

        $has_bak = ($this->_request->get_post('has_bak')) ? true : false;

        $fileObj = kernel::single('site_explorer_file');
        $dir = $this->get_theme_dir($theme, $open_path);
        if($has_bak){
            $fileObj->backup_file($dir . '/' . $file_name);
        }
        $fileObj->save_image($dir . '/' . $file_name, $_FILES['upfile']);
        $this->end(true, app::get('site')->_('保存成功'));
    }//End Function

    /*
     * 删除文件
     */
    public function delete_file() 
    {
        $this->begin();
        $theme = $this->_request->get_get('theme');
        $open_path = $this->_request->get_get('open_path');
        $file_name = $this->_request->get_get('file_name');

        if(!$this->check($theme,$msg))   $this->_error($msg);

        $dir = $this->get_theme_dir($theme, $open_path);
        $fileObj = kernel::single('site_explorer_file');
        $fileObj->delete_file($dir . '/' . $file_name);
        $filter=array(
                 'id' => $theme,
                 'dir' => $dir,
                 'show_bak' => true,
                 'type' => 'all'
             );
        $file_baklist = $fileObj->get_file_baklist($filter, $file_name);
        if(is_array($file_baklist)){
            foreach($file_baklist AS $fileinfo){
                $fileObj->delete_file($dir . '/' . $fileinfo['name']);
            }
        }
        $this->end(true, app::get('site')->_('删除成功'));
    }//End Function

    /*
     * 恢复文件
     */
    public function recover_file() 
    {
        $this->begin();
        $theme = $this->_request->get_get('theme');
        $open_path = $this->_request->get_get('open_path');
        $file_name = $this->_request->get_get('file_name');

        if(!$this->check($theme,$msg))   $this->_error($msg);

        $dir = $this->get_theme_dir($theme, $open_path);
        $fileObj = kernel::single('site_explorer_file');
        $fileObj->recover_file($dir . '/' . $file_name);
        $this->end(true, app::get('site')->_('恢复成功'));
    }//End Function

}//End Class
