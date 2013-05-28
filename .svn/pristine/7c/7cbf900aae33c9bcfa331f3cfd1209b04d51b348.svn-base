<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_theme_widget 
{

    public function count_widgets_by_theme($sTheme){
        $data = app::get('site')->model('widgets_instance')->db->selectlimit('select count("widgets_id") as num from sdb_site_widgets_instance where  core_file like "'.addslashes($sTheme).'%"');
        return $data[0]['num'];
    }

    public function delete_widgets_by_theme($sTheme){
        $flag = app::get('site')->model('widgets_instance')->db->exec('delete from sdb_site_widgets_instance where core_file like "'.addslashes($sTheme).'/%"');
        if($flag)
        	return app::get('site')->model('themes_tmpl')->delete(array('theme'=>$sTheme));
        return $flag;
    }

    public function insert_widgets($aData)
    {
        //modfity by EDwin 2010/5/7
        if($aData['base_file']){
            $aData['core_file'] = substr($aData['base_file'], strpos($aData['base_file'], ':')+1);
            $aData['core_slot'] = $aData['base_slot'];
            $aData['core_id'] = $aData['base_id'];
            unset($aData['base_file']);
            unset($aData['base_slot']);
            unset($aData['base_id']);
        }//fix template install
        $aData['modified'] = time();
        return app::get('site')->model('widgets_instance')->insert($aData);
    }

    public function save_widgets($widgets_id, $aData) 
    {
        if(!is_numeric($widgets_id))    return false;
        $aData['widgets_id'] = $widgets_id;
        $aData['modified'] = time();
        return app::get('site')->model('widgets_instance')->save($aData);
    }//End Function

    public function save_all($widgetsSet, $files) 
    {
        $i=0;
        $slots = array();
        $return = array();
        $model = app::get('site')->model('widgets_instance');
        foreach((array)$widgetsSet as $widgets_id=>$widgets){
            $widgets['modified'] = time();
            $widgets['widgets_order'] = $i++;
            $sql = '';
            if(is_numeric($widgets_id)){
                $slots[$widgets['core_file']][]=$widgets_id;
                $sData = $widgets;
                $sData['widgets_id'] = $widgets_id;
                if(!$model->save($sData)){
                    return false;
                }
            }elseif(preg_match('/^tmp_([0-9]+)$/i',$widgets_id,$match)){

                $wg = $_SESSION['_tmp_wg_insert'][$match[1]];
                $setting = $this->widgets_info($wg['widgets_type'], $wg['app'], $wg['theme']);

                $widgets = array_merge(
                    $widgets,
                    $wg,
                    array(  'vary'=>$setting['vary'],
                            'scope'=> is_array($setting['scope'])?(','.implode($setting['scope'],',').','):$setting['scope'])
                );

                $widgets_id = $model->insert($widgets);

                if(!ecos_site_lib_theme_widget_save_all($widgets_id, $widgets, $match, $return, $slots)){
                    return false;
                }
            }
            if(!strpos($widgets['core_file'],':')){
                kernel::single('site_theme_tmpl')->touch_tmpl_file($widgets['core_file']);
            }
        }
        if(is_array($files)){
            foreach($files as $file){
                if(is_array($slots[$file])&&count($slots[$file])>0){
                    $model->db->exec('delete from sdb_site_widgets_instance where widgets_id not in('.implode(',',$slots[$file]).') and core_file="'.$file.'"');
                }else{
                    $model->db->exec('delete from sdb_site_widgets_instance where core_file="'.$file.'"');
                }
                if(!strpos($file, ':')){
                    kernel::single('site_theme_tmpl')->touch_tmpl_file($file);
                }
            }
        }
        return $return;
    }//End Function

    public function widgets_exists($name, $app, $theme) 
    {
        $data = $this->widgets_config($name, $app, $theme);
        if(is_dir($data['dir'])){
            return $data['dir'];
        }else{
            return false;
        }
    }//End Function

    public function widgets_info($name, $app, $theme, $key=null) 
    {
        if($name&&$widgets_dir = $this->widgets_exists($name, $app, $theme)){
            include($widgets_dir . '/widgets.php');
            $setting['type'] = $name;
            return (is_null($key)) ? $setting : (isset($setting[$key]) ? $setting[$key] : '');
        }else{
            false;
        }            
    }//End Function

    public function get_widgets_info($name, $app, $key=null) 
    {
        //todo:兼容老版本，无模板挂件
        return $this->widgets_info($name, $app, '', $key);
    }//End Function

    public function widgets_config($name, $app, $theme) 
    {
        if(empty($theme)){
            $app = ($app) ? $app : 'b2c';
            if(defined('CUSTOM_CORE_DIR') && file_exists(CUSTOM_CORE_DIR . '/' . $app . '/widgets/' . $name)){
                $data['dir'] = CUSTOM_CORE_DIR . '/' . $app . '/widgets/' . $name;
            }else{
                $data['dir'] = APP_DIR . '/' . $app . '/widgets/' . $name;
            }
            $data['app'] = app::get($app);
            $data['url'] = $data['app']->widgets_full_url . '/' . $name;
            ecos_site_lib_theme_widget_widgets_config_empty($name, $data, $app);
        }else{
            $data['dir'] = THEME_DIR . '/' . $theme . '/widgets/' . $name;
            $data['app'] = null;
            $data['url'] = kernel::base_url(1) . '/themes/' . $theme . '/widgets/' . $name;
            ecos_site_lib_theme_widget_widgets_config_theme($name, $data, $theme);
        }
        return $data;
    }//End Function

    public function get_libs($theme, $type='') 
    {
        $data = app::get('site')->model('widgets')->select()->where('theme = ?', '')->or_where('theme = ?', $theme)->instance()->fetch_all();
        $widgetsLib = array();
        $order=array();
        if($type==null){
            foreach($data AS $val){
                $info = $this->widgets_info($val['name'], $val['app'], $val['theme']);
                ecos_site_lib_theme_widget_widgets_get_libs_notype($info, $val, $widgetsLib);
            }
        }else{
            foreach($data AS $val){
                $info = $this->widgets_info($val['name'], $val['app'], $val['theme']);
                ecos_site_lib_theme_widget_widgets_get_libs_type($info, $type, $val, $widgetsLib);
            }
            array_multisort($order, SORT_DESC, $widgetsLib['list']);
        }
        //asort($widgetsLib);
        //echo '<PRE>';
        //print_r($widgetsLib);exit;
        return $widgetsLib;

    }//End Function

    public function get_this_widgets_info($widgets, $app, $theme){
        $info = $this->widgets_info($widgets, $app, $theme);
        $widgetsLib = array('description'=>$info['description'],'catalog'=>$info['catalog'],'label'=>$info['name']);
        return $widgetsLib;
    }

    public function admin_load($file, $slot, $id=null, $edit_mode=false){
        //print_r(func_get_args());
        if(!$this->fastmode && $edit_mode){
            $this->fastmode=true;
        }
        $selectObj = app::get('site')->model('widgets_instance')->select()->where('core_file = ?', $file)->order('widgets_order ASC');
        if(!$id){
            $rows = $selectObj->where('core_slot = ?', $slot)->instance()->fetch_all();
        }else{
            $rows = $selectObj->where('core_id = ?', $id)->instance()->fetch_all();
        }
        $smarty = kernel::single('site_admin_render');
        $files = $smarty->_files;
        $_wgbar = $smarty->_wgbar;

        if(!strpos($file, ':')){
            $theme= substr($file,0,strpos($file,'/'));
        }else{
            $theme = kernel::single('site_theme_base')->get_default();
        }
        
        $wights_border= kernel::single('site_theme_base')->get_border_from_themes($theme);
        foreach($rows as $widgets){
            if($widgets['widgets_type']=='html')$widgets['widgets_type']='usercustom';
            $widgets['html'] = $this->fetch($widgets);

            $title=$widgets['title']?$widgets['title']:$widgets['widgets_type'];
            $wReplace=Array('<{$body}>','<{$title}>','<{$widgets_classname}>','"<{$widgets_id}>"');
            $wArt=Array($this->admin_wg_border($widgets,$theme),$widgets['title'],
                $widgets['classname']
                ,($widgets['domid']?$widgets['domid']:'widgets_'.$widgets['widgets_id']).' widgets_id="'.$widgets['widgets_id'].'"  title="'.$title.'"'.' widgets_theme="' . $theme . '"');

            if($widgets['border']!='__none__' && $wights_border[$widgets['border']]){
                $content=preg_replace("/(class\s*=\s*\")|(class\s*=\s*\')/","$0shopWidgets_box ",$wights_border[$widgets['border']],1);
                $widgets_box=str_replace($wReplace,$wArt, $content);
            }else{
                $widgets_box= '<div class="shopWidgets_box" widgets_id="'.$widgets['widgets_id'].'" title="'.$title.'" widgets_theme="'.$theme.'">'.$this->admin_wg_border($widgets,$theme).'</div>';
            }
            $widgets_box=preg_replace("/<object[^>]*>([\s\S]*?)<\/object>/i","<div class='sWidgets_flash' title='Flash'>&nbsp;</div>",$widgets_box);
            $replacement=array("'onmouse'i","'onkey'i","'onmousemove'i","'onload'i","'onclick'i","'onselect'i","'unload'i");
            $widgets_box=preg_replace($replacement,array_fill(0,count($replacement),'xshopex'),$widgets_box);
            $widgets_box = str_replace('%THEME%', kernel::base_url(1).'/themes/'.$theme, $widgets_box);
            echo preg_replace("/<script[^>]*>([\s\S]*?)<\/script>/i","",$widgets_box);
            
        }
        $smarty->_files = $files;
        $smarty->_wgbar = $_wgbar;
    }//End Function

    public function fetch($widgets, $widgets_id=null){
        
        $widgets_config = $this->widgets_config($widgets['widgets_type'], $widgets['app'], $widgets['theme']);
        $widgets_dir = $widgets_config['dir'];
                
        if(!is_dir($widgets_dir)){
            return app::get('site')->_('版块'). $widgets_config['app']->app_id . '|' . $widgets['widgets_type'].app::get('site')->_('不存在.');
        }
        
        $func_file = $widgets_config['func'];
        
        if(file_exists($func_file)){
            $this->_errMsg = null;
            $this->_run_failed = false;
            include_once($func_file);
            if(function_exists($widgets_config['run'])){
                
                $menus = array();
                $func = $widgets_config['run'];

                kernel::single('site_admin_render')->pagedata['data'] = $func($widgets['params'], kernel::single('site_admin_render'));
                kernel::single('site_admin_render')->pagedata['menus'] = &$menus;
            }
            if($this->_run_failed)
                return $this->_errMsg;
        }

        kernel::single('site_admin_render')->pagedata['setting'] = $widgets['params'];
        kernel::single('site_admin_render')->pagedata['widgets_id'] = $widgets_id;
        
        if(file_exists($widgets_dir . '/_preview.html')){
            $return = kernel::single('site_admin_render')->fetch_admin_widget($widgets_dir . '/_preview.html');
            if($return!==false){
                ecos_site_lib_theme_widget_prefix_content($return, $widgets_config['url']);
            }
            return $return;
        }else{
            if($this->fastmode){
                return '<div class="widgets-preview">'.$widgets['widgets_type'].'</div>';
            }
            $return = kernel::single('site_admin_render')->fetch_admin_widget($widgets_dir.'/'.$widgets['tpl']);
            if($return!==false){
                ecos_site_lib_theme_widget_prefix_content($return, $widgets_config['url']);
            }
            return $return;
            //return '<div class="widgets-preview">ddfdfdfddfdf</div>';
        }
    }//End Function

    public function admin_wg_border($widgets,$theme,$type=false){

        if($type){
            $content="{$widgets['html']}";
            $wReplace=Array('<{$body}>','<{$title}>','<{$widgets_classname}>','"<{$widgets_id}>"');
            $title=$widgets['title']?$widgets['title']:$widgets['widgets_type'];
            $wArt=Array($content,$widgets['title'],
                $widgets['classname']
                ,($widgets['domid']?$widgets['domid']:'widgets_'.$widgets['widgets_id']).' widgets_id="'.$widgets['widgets_id'].'"  title="'.$title.'"'.' widgets_theme="' . $theme . '"');
            if(!empty($widgets['border']) && $widgets['border']!='__none__'){
                $wights_border = kernel::single('site_theme_base')->get_border_from_themes($theme);
                $content=preg_replace("/(class\s*=\s*\")|(class\s*=\s*\')/","$0shopWidgets_box ",$wights_border[$widgets['border']],1);
                $tpl=str_replace($wReplace,$wArt, $content);
            }else{
                $tpl='<div class="shopWidgets_box" widgets_id="'.$widgets['widgets_id'].'" title="'.$title.'" widgets_theme="'.$theme.'">'.$content.'</div>';
            }
        }else{
            $tpl="{$widgets['html']}";
        }

        return trim(preg_replace('!\s+!', ' ', $tpl));
    }

    public function editor($widgets, $widgets_app, $widgets_theme, $theme, $values=false){

        $return = array();
        $widgets_config = $this->widgets_config($widgets, $widgets_app, $widgets_theme);
        $widgets_dir = $widgets_config['dir'];

        $setting = $this->widgets_info($widgets, $widgets_app, $widgets_theme);

        ecos_site_lib_theme_widget_editor($widgets, $values, $setting, $widgets_dir, $return);
        
        $return['borders'] = kernel::single('site_theme_base')->get_theme_borders($theme);
        $return['borders']['__none__']=app::get('site')->_('无边框');

        if(file_exists($widgets_dir.'/_config.html')){

            $smarty = kernel::single('site_admin_render');
            $smarty->tmpl_cachekey('widget_modifty' , true);

            $sFunc=$widgets_config['crun'];
            $sFuncFile = $widgets_config['cfg'];
            if(file_exists($sFuncFile)){
                include_once($sFuncFile);
                if(function_exists($sFunc)){
                    $smarty->pagedata['data'] = $sFunc($widgets_config['app']);
                }
            }

            $smarty->pagedata['setting'] = &$values;

            $compile_code = $smarty->fetch_admin_widget($widgets_dir.'/_config.html');
            if($compile_code){
                ecos_site_lib_theme_widget_prefix_content($compile_code, $widgets_config['url']);
            }
            $return['html'] = $compile_code;    
        }
        //echo '<PRE>';
        //print_r($return);exit;
        return $return;
    }

}//End Class
