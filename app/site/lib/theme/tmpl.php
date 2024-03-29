<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_theme_tmpl 
{

    public function get_default($type, $theme) 
    {
        return app::get('site')->getConf('custom_template_'.$theme.'_'.$type);
    }//End Function

    public function set_default($type, $theme, $value) 
    {
        return app::get('site')->setConf('custom_template_'.$theme.'_'.$type, $value);
    }//End Function

    public function del_default($type, $theme) 
    {
        return app::get('site')->setConf('custom_template_'.$theme.'_'.$type, '');
    }//End Function

    public function set_all_tmpl_file($theme) 
    {
        $row = app::get('site')->model('themes_tmpl')->select()->columns('tmpl_path')->where('theme = ?', $theme)->instance()->fetch_col();
        return app::get('site')->setConf('custom_template_'.$theme.'_all_tmpl', $row['tmpl_path']);
    }//End Function

    public function get_all_tmpl_file($theme) 
    {
        return app::get('site')->getConf('custom_template_'.$theme.'_all_tmpl');
    }//End Function

    public function tmpl_file_exists($tmpl_file, $theme) 
    {
        $all = $this->get_all_tmpl_file($theme);
        $all[] = 'block/header.html';
        $all[] = 'block/footer.html';   //头尾文件
        if(is_array($all)){
            return in_array($tmpl_file, $all);
        }else{
            return false;
        }
    }//End Function

    public function get_edit_list($theme) 
    {
        $data = app::get('site')->model('themes_tmpl')->getList('*', array("theme"=>$theme));
        if(is_array($data)){
            foreach($data AS $value){
                if($this->get_default($value['tmpl_type'], $theme) == $value['tmpl_path'])
                    $value['default'] = 1;

                $ret[$value['tmpl_type']][] = $value;
            }
        }
        return $ret;
    }//End Function
    
    public function install($theme) 
    {
        $list = array();
        $this->__get_all_files(THEME_DIR . '/' . $theme, $list, false);
        if(file_exists(THEME_DIR.'/'.$theme.'/cart.html')){
            if(!file_exists(THEME_DIR.'/'.$theme.'/order_detail.html')){
                copy(THEME_DIR.'/'.$theme.'/cart.html',THEME_DIR.'/'.$theme.'/order_detail.html');
            }
            if(!file_exists(THEME_DIR.'/'.$theme.'/order_index.html')){
                copy(THEME_DIR.'/'.$theme.'/cart.html',THEME_DIR.'/'.$theme.'/order_index.html');
            }
        }
        $ctl = $this->get_name();
        foreach($list AS $key=>$value){
            $file_name = basename($value, '.html');
            if(!strpos($file_name,'.')){
                if(($pos=strpos($file_name,'-'))){
                    $type=substr($file_name,0,$pos);
                    $file[$type][$key]['name']=$ctl[substr($file_name,0,$pos)];
                    $file[$type][$key]['file']=$file_name.'.html';
                }else{
                    $type=$file_name;
                    $file[$file_name][$key]['name']=$ctl[$file_name];
                    $file[$file_name][$key]['file']=$file_name.'.html';
                    //$file[$key]['name']=$ctl[$file_name];
                }
                
                touch(THEME_DIR . '/' . $theme . '/' . $file_name . '.html');
                
                if($type && array_key_exists($type, $ctl)){
                    $array = array(
                        'theme'=>$theme,
                        'tmpl_type'=>$type,
                        'tmpl_name'=>$file_name.'.html',
                        'tmpl_path'=>$file_name.'.html',
                        'version'=>filemtime(THEME_DIR . '/' . $theme . '/' . $file_name . '.html'),
                        'content'=>file_get_contents(THEME_DIR . '/' . $theme . '/' . $file_name . '.html')
                    );
                    $this->insert($array);
                    if(!$this->get_default($type, $theme)){
                        $this->set_default($type, $theme, $file_name.'.html');
                    }
                }
            }
        }
    }//End Function

    public function insert($data) 
    {
        if(app::get('site')->model('themes_tmpl')->insert($data)){
            $this->set_all_tmpl_file($data['theme']);
            return true;
        }else{
            return false;
        }
    }//End Function

    public function insert_tmpl($data) 
    {
        $dir = THEME_DIR . '/' . $data['theme'];
        if(!is_dir($dir))   return false;
        if(empty($data['tmpl_type']) || empty($data['content']))    return false;
        $data['tmpl_path'] = strtolower(preg_replace('/[^a-z0-9]/', '', $data['tmpl_path']));
        if($data['tmpl_path']){
            $target = $dir . '/' . $data['tmpl_type'] . '-' . $data['tmpl_path'] . '.html';
            if(is_file($target)){
                $target = '';
            }
        }
        if(empty($target)){
            $flag = true;
            for($i=1; $flag; $i++){
                $target = sprintf('%s/%s-(%s).html', $dir, $data['tmpl_type'], $i);
                if(file_exists($target))    continue;
                $flag = false;
            }
        }
        if(file_put_contents($target, $data['content'])){
            $data['tmpl_path'] = basename($target);
            $data['tmpl_name'] = ($data['tmpl_name']) ? $data['tmpl_name'] : basename($target);
            $data['version'] = filemtime($target);
            return $this->insert($data);
        }
        return false;
    }//End Function

    public function copy_tmpl($tmpl, $theme) 
    {
        $source = THEME_DIR . '/' . $theme . '/' . $tmpl;
        if(!is_file($source))   return false;
        $data = app::get('site')->model('themes_tmpl')->getList('*', array('theme'=>$theme, 'tmpl_path'=>$tmpl));
        $data = $data[0];
        if(empty($data))    return false;
        $flag = true;
        for($i=1; $flag; $i++){
            $target = sprintf('%s/%s/%s-(%s).html', THEME_DIR, $theme, $data['tmpl_type'], $i);
            if(file_exists($target))    continue;
            copy($source, $target);
            $flag = false;
        }
        unset($data['id']);
        $data['tmpl_path'] = basename($target);
        $data['tmpl_name'] = basename($target);
        if($this->insert($data)){
            $widgets = app::get('site')->model('widgets_instance')->getList('*', array('core_file'=>$theme.'/'.$tmpl));
            foreach($widgets AS $widget){
                unset($widget['widgets_id']);
                $widget['core_file'] = $theme . '/' . basename($target);
                $widget['modified'] = time();
                app::get('site')->model('widgets_instance')->insert($widget);
            }
            return true;
        }else{
            return false;
        }
    }//End Function

    public function delete_tmpl_by_theme() 
    {
        //不删除实体文件，只处理数据库和conf
        $datas = app::get('site')->model('themes_tmpl')->getList('tmpl_path', array('theme'=>$theme));
        foreach($datas AS $data){
            $this->delete_tmpl($data['tmpl_path'], $theme);
        }
    }//End Function

    public function delete_tmpl($tmpl, $theme) 
    {
        $source = THEME_DIR . '/' . $theme . '/' . $tmpl;
        if(!is_file($source))   return false;
        $data = app::get('site')->model('themes_tmpl')->getList('*', array('theme'=>$theme, 'tmpl_path'=>$tmpl));
        if($data[0]['id'] > 0){
            if(app::get('site')->model('themes_tmpl')->delete(array('id'=>$data[0]['id']))){
                app::get('site')->model('widgets_instance')->delete(array('core_file'=>$theme.'/'.$tmpl));
                $this->set_all_tmpl_file($data[0]['theme']);
                if($this->get_default($data[0]['tmpl_type'], $theme) == $data[0]['tmpl_path']){
                    $this->del_default($data[0]['tmpl_type'], $theme);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }//End Function

    private function __get_all_files($sDir, &$aFile, $loop=true){
        if($rHandle=opendir($sDir)){
            while(false!==($sItem=readdir($rHandle))){
                if ($sItem!='.' && $sItem!='..' && $sItem!='' && $sItem!='.svn' && $sItem!='_svn'){
                    if(is_dir($sDir.'/'.$sItem)){
                        if($loop){
                            $this->__get_all_files($sDir.'/'.$sItem,$aFile);
                        }
                    }else{
                        $aFile[]=$sDir.'/'.$sItem;
                    }
                }
            }
            closedir($rHandle);
        }
    }

    public function get_name(){
        $ctl = $this->__get_tmpl_list();
        return $ctl;
    }
    
    
    public function get_list_name($name) 
    {
        $name = rtrim(strtolower($name),'.html');
        $ctl = $this->__get_tmpl_list();
        return $ctl[$name];
    }//End Function
    
    private function __get_tmpl_list() {
        $ctl = array(
            'index'=>app::get('site')->_('首页'),
            'gallery'=>app::get('site')->_('商品列表页'),
            'product'=>app::get('site')->_('商品详细页'),
            'comment'=>app::get('site')->_('商品评论/咨询页'),
            'article'=>app::get('site')->_('文章页'),
            'articlelist'=>app::get('site')->_('文章列表页'),
            'gift'=>app::get('site')->_('赠品页'),
            'package'=>app::get('site')->_('捆绑商品页'),
            'brandlist'=>app::get('site')->_('品牌专区页'),
            'brand'=>app::get('site')->_('品牌商品展示页'),
            'cart'=>app::get('site')->_('购物车页'),
            'search'=>app::get('site')->_('高级搜索页'),
            'passport'=>app::get('site')->_('注册/登录页'),
            'member'=>app::get('site')->_('会员中心页'),
            'page'=>app::get('site')->_('站点栏目单独页'),
            'order_detail'=>app::get('site')->_('订单详细页'),
            'order_index'=>app::get('site')->_('订单确认页'),
            'splash'=>app::get('site')->_('信息提示页'),
            'default'=>app::get('site')->_('默认页'),
        );
        foreach( kernel::servicelist("site.site_theme_tmpl") as $object ) {
            if( method_exists($object,'__get_tmpl_list') ) {
                $arr = $object->__get_tmpl_list($ctl);
                if( $arr ) {
                    foreach( $arr as $key => $val ) {
                        if( $ctl[$key] ) continue;
                        $ctl[$key] = $val;
                    }
                }
            }
        }
        return $ctl;
    }

    public function touch_theme_tmpl($theme) 
    {
        $rows = app::get('site')->model('themes_tmpl')->select()->columns('tmpl_path')->where('theme = ?', $theme)->instance()->fetch_all();
        if($rows){
            array_push($rows, array('tmpl_path'=>'block/header.html'), array('tmpl_path'=>'block/footer.html'));
            foreach($rows AS $row){
                $this->touch_tmpl_file($theme . '/' . $row['tmpl_path']);
            }
            kernel::single('site_theme_base')->set_theme_cache_version($theme);
        }

        $cache_keys = kernel::database()->select('SELECT `prefix`, `key` FROM sdb_base_kvstore WHERE `prefix` IN ("cache/template", "cache/theme")');
        foreach($cache_keys as $value)
        {
            base_kvstore::instance($value['prefix'])->get_controller()->delete($value['key']);
        }
        kernel::database()->exec('DELETE FROM sdb_base_kvstore WHERE `prefix` IN ("cache/template", "cache/theme")');

        cachemgr::init(true);
        cachemgr::clean($msg);
        cachemgr::init(false);

        return true;
    }//End Function


    public function touch_tmpl_file($tmpl, $time=null) 
    {
        if(empty($time))    $time = time();
        $source = THEME_DIR . '/' . $tmpl;
        if(is_file($source)){
            return @touch($source, $time);
        }else{
            return false;
        }
    }//End Function

    function output_pkg($theme){
        $tar = kernel::single('base_tar');
        $workdir = getcwd();

        if(chdir(THEME_DIR.'/'.$theme)){
            $this->__get_all_files('.',$aFile);
            for($i=0;$i<count($aFile);$i++){
                if($f = substr($aFile[$i],2)){
                    if($f!='theme.xml'){
                        $tar->addFile($f);
                    }
                }
            }
            if(is_file('info.xml')){
                $tar->addFile('info.xml',file_get_contents('info.xml'));
            }
            $tar->addFile('theme.xml',$this->make_configfile($theme));

            $aTheme = kernel::single('site_theme_base')->get_theme_info($theme);

            kernel::single('base_session')->close();

            $name = kernel::single('base_charset')->utf2local(preg_replace('/\s/','-',$aTheme['name'].'-'.$aTheme['version']),'zh');
            @set_time_limit(0);

            header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header('Content-type: application/octet-stream');
            header('Content-type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$name.'.tgz"');
            $tar->getTar('output');
            chdir($workdir);
        }else{
            chdir($workdir);
            return false;
        }
    }

    public function make_configfile($theme){
        $aTheme = kernel::single('site_theme_base')->get_theme_info($theme);

        //$aWidget['widgets'] = app::get('site')->model('widgets_instance')->getList('*', array('core_file|head'=>$theme.'/'));
        $aWidget['widgets'] = app::get('site')->model('widgets_instance')->select()->where("core_file LIKE '".$theme."/%'")->instance()->fetch_all();
        foreach($aWidget['widgets'] as $i => &$widget){
            $widget['core_file'] = str_replace($theme.'/', '', $widget['core_file']);
            $widget['params'] = serialize($widget['params']);
        }
        $aWidget['widgets_proinstance'] = app::get('site')->model('widgets_proinstance')->select()->where('level = ?', 'theme')->where('flag = ?', $theme)->instance()->fetch_all();
        foreach($aWidget['widgets_proinstance'] AS $k=>&$instance){
            $instance['params'] = serialize($instance['params']);
        }
        //$aTheme['config']['config'] = $aTheme['config']['config'];
        //$aTheme['config']['views'] = $aTheme['views'];
        $aTheme['id'] = $aTheme['theme'];
        $aTheme=array_merge($aTheme, $aWidget);

        $render = kernel::single('base_render');
        $render->pagedata = $aTheme;
        $sXML = $render->fetch('admin/theme/theme.xml', 'site');

        return $sXML;
    }

}//End Class
