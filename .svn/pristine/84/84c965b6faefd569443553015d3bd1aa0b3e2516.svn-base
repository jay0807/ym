<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_finder_theme
{
    public $addon_cols='theme,stime,author,site,version';

    public $column_preview='预览';
    public $column_preview_width='140';
    public function column_preview($row){
        $dir = THEME_DIR . '/' . $row[$this->col_prefix.'theme'];
        if(is_dir($dir)){
            $current_theme = kernel::single('site_theme_base')->get_default();
            
            $current_sytle = kernel::single('site_theme_base')->get_theme_style($row[$this->col_prefix.'theme']);

            $preview = ($current_sytle['preview']) ? $current_sytle['preview'] : 'preview.jpg';
            $_tm = $row[$this->col_prefix.'theme'];
            $_active = ($_tm == $current_theme);
            
            
            if($_active){
                $style_addon = "border:2px solid #6888C8; border-bottom:none";
                $style_addon2 = "border-color:#6888C8;";
            }
			$theme_url = defined('THEMES_IMG_URL') ? THEMES_IMG_URL : kernel::base_url(1) . '/themes';
			$_html =  sprintf('<div onmouseover="$(this).set(\'detail\',$(this).getParent(\'.row\').getElement(\'.btn-detail-open\').get(\'detail\'));" style="border:2px solid #E4EAF1; width: 120px; height: 140px;cursor:pointer;text-align:center;overflow:hidden; background:#fff;'.$style_addon.'"><img onload="$(this).zoomImg
			(120,136);" src="%s" id="%s" style="float:none"></div>', $theme_url. "/" . $row[$this->col_prefix.'theme'] . '/' . $preview . '?' . time(), $row[$this->col_prefix.'theme'].'_img');
            
            $_html.='<div style="width:120px;line-height:20px; background:#E4EAF1; border:2px solid #E4EAF1;'.$style_addon2.'">';
            
            if($_active){   
            $_html.='<div class="t-c" style="font-weight:bold; color:#fff; background:#6888C8; ">'.app::get('site')->_('已启用').'</div>';
            }else{
            $_html.='<div class="t-c" style=""><a style="color:#000" href="index.php?app=site&ctl=admin_theme_manage&act=set_default&theme='.$_tm.'&finder_id='.$_GET['_finder']['finder_id'].'">'.app::get('site')->_('启用此模板').'</a></div>';  
            }
            
            $_html.=$this->theme_fullstyles($row);
            
            $_html.='</div>';
                
                
            return $_html;
        }else{
            
            return '<div>'.app::get('site')->_('模板目录已被移除').'</div>';
        }
    }

    /*public $column_fullstyles = '样式';
    public $column_fullstyles_width = '40';*/
    private function theme_fullstyles($row) 
    {
        $styles = kernel::single('site_theme_base')->get_theme_styles($row[$this->col_prefix.'theme']);
        $render = app::get('site')->render();
        $render->pagedata['styles'] = $styles;
        $render->pagedata['theme'] = $row[$this->col_prefix.'theme'];
        $render->pagedata['preview_prefix'] = kernel::base_url(1) . '/themes/' . $row[$this->col_prefix.'theme'];
        $render->pagedata['current'] = kernel::single('site_theme_base')->get_theme_style($row[$this->col_prefix.'theme']);
        return $render->fetch('admin/theme/manage/style.html');
    }//End Function


    /*
    public $column_use = '使用';
    public $column_use_width = '140';
    public function column_use($row) 
    {
        $current_theme = kernel::single('site_theme_base')->get_default();
        if($row[$this->col_prefix.'theme'] == $current_theme){
            return app::get('site')->_('使用中');
        }else{
            return '<a href="javascript:;" onClick="javascript:W.page(\'index.php?app=site&ctl=admin_theme_manage&act=set_default&theme='.$row[$this->col_prefix.'theme'].'\')" >'.app::get('site')->_('启用').'</a>';
        }
    }//End Function
*/
    public $detail_tmpl = '模板编辑';
    public function detail_tmpl($id){
        $data = app::get('site')->model('themes')->getList('*', array('theme'=>$id));
        $render = app::get('site')->render();
        $theme = $data[0]['theme'];
        $render->pagedata['list'] = kernel::single('site_theme_tmpl')->get_edit_list($theme);
        $render->pagedata['types'] = kernel::single('site_theme_tmpl')->get_name();
        $render->pagedata['theme'] = $theme;
        return $render->fetch('admin/theme/tmpl/frame.html');
    }

    public $detail_info = '基本信息';
    public function detail_info($id) 
    {
        $data = app::get('site')->model('themes')->getList('*', array('theme'=>$id));
        $render = app::get('site')->render();
        $row = $data[0];
        $row['config'] = $row['config'];
        $render->pagedata['theme'] = $id;
        $render->pagedata['row'] = $row;
        
        $widgets = app::get('site')->model('widgets')->select()->where('theme = ?', $row['theme'])->instance()->fetch_all();
        foreach($widgets AS $k=>$v){
            $widgets[$k]['info'] = kernel::single('site_theme_widget')->widgets_info($v['name'], $v['app'], $v['theme']);
        }
        $render->pagedata['widgets'] = $widgets;
        
        
        $option = '';
        if(file_exists(THEME_DIR . '/' . $id . '/theme.xml')) {
            $option .= '<option value="theme.xml">'.app::get('site')->_('默认').'</option>';
        }
        if(file_exists(THEME_DIR . '/' . $id . '/theme_bak.xml')) {
            $option .= '<option value="theme_bak.xml">'.app::get('site')->_('最近一次备份').'</option>';
        }
        $render->pagedata['resetoption'] = $option;

        return $render->fetch('admin/theme/detail/info.html');
    }//End Function

    public $detail_files = '模板文件管理';
    public function detail_files($id) 
    {
        $data = app::get('site')->model('themes')->getList('*', array('theme'=>$id));
        $render = app::get('site')->render();
        $theme = $data[0]['theme'];
        $render->pagedata['init_url'] = 'index.php?app=site&ctl=admin_explorer_theme&act=directory&theme=' . $theme;
        $render->pagedata['finder_id'] = $_GET['finder_id'];
        return $render->fetch('admin/explorer/theme/index.html');
    }//End Function
    
    public $detail_proinstance = '模板挂件实例';
    public function detail_proinstance($id){
        $render = app::get('site')->render();
        $render->pagedata['theme'] = $id;
        $render->pagedata['instances'] = app::get('site')->model('widgets_proinstance')->getList('*', array('level'=>'theme', 'flag'=>$id));
        return $render->fetch('admin/theme/tmpl/proinstance.html');
    }


    /*
    public $detail_back = '备份与还原';
    public function detail_back($theme) 
    {
        $render = app::get('site')->render();
        $render->pagedata['theme'] = $theme;
        $option = '';
        if(file_exists(THEME_DIR . '/' . $theme . '/theme.xml')) {
            $option .= '<option value="theme.xml">'.app::get('site')->_('默认').'</option>';
        }
        if(file_exists(THEME_DIR . '/' . $theme . '/theme_bak.xml')) {
            $option .= '<option value="theme_bak.xml">'.app::get('site')->_('备份').'</option>';
        }
        $render->pagedata['option'] = $option;
        return $render->fetch('admin/theme/tmpl/backup.html');
    }//End Function
    
    public $detail_download = '下载模板';
    public function detail_download($theme) 
    {
        $render = app::get('site')->render();
        $render->pagedata['theme'] = $theme;
        return $render->fetch('admin/theme/tmpl/download.html');
    }//End Function

    public $detail_cache = '模板缓存';
    public function detail_cache($theme) 
    {
        $render = app::get('site')->render();
        $render->pagedata['theme'] = $theme;
        return $render->fetch('admin/theme/tmpl/cache.html');
    }//End Function
   
    */
}//End Class
