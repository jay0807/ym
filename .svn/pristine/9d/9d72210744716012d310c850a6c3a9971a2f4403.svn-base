<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_theme_helper 
{
    function function_header(){
        $ret='<base href="'.kernel::base_url(1).'"/>';
        $path = app::get('site')->res_full_url;
		
		$css_min = (defined('DEBUG_CSS') && constant('DEBUG_CSS')) ? '' : '_min';
        $ret.= '<link rel="stylesheet" href="'.$path.'/framework'.$css_min.'.css" type="text/css" />';
        $ret.='<link rel="stylesheet" href="'.$path.'/widgets_edit'.$css_min.'.css" type="text/css" />';
        
        $ret .= kernel::single('base_component_ui')->lang_script(array('src'=>'lang.js', 'app'=>'site'));
        if(defined('DEBUG_JS') && constant('DEBUG_JS')){
            $ret.= '<script src="'.$path.'/js/mootools.js"></script>
					<script src="'.$path.'/js/moomore.js"></script>
					<script src="'.$path.'/js/jstools.js"></script>
					<script src="'.$path.'/js/switchable.js"></script>
					<script src="'.$path.'/js/dragdropplus.js"></script>
					<script src="'.$path.'/js/shopwidgets.js"></script>';
        }else{
            $ret.= '<script src="'.$path.'/js_mini/moo_min.js"></script>
                <script src="'.$path.'/js_mini/shopwidgets_min.js"></script>';
        }

        if($theme_info=(app::get('site')->getConf('site.theme_'.app::get('site')->getConf('current_theme').'_color'))){
            $theme_color_href=kernel::base_url(1).'/themes/'.app::get('site')->getConf('current_theme').'/'.$theme_info;
            $ret.="<script>
            window.addEvent('domready',function(){
                new Element('link',{href:'".$theme_color_href."',type:'text/css',rel:'stylesheet'}).injectBottom(document.head);               
             });
            </script>";
        }
       /* $ret .= '<script>
					window.addEvent(\'domready\',function(){(parent.loadedPart[1])++});
                    parent.document.getElementById(\'loadpart\').style.display="none";
                    parent.document.getElementById(\'body\').style.display="block";
                </script>';
*/
        foreach(kernel::serviceList('site_theme_view_helper') AS $service){
            if(method_exists($service, 'function_header')){
                $ret .= $service->function_header();
            }
        }

        return $ret;
    }

    function function_footer(){
        return '<div id="drag_operate_box" class="drag_operate_box" style="visibility:hidden;">
            <div class="drag_handle_box">
            <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
            <td><span class="dhb_title">'.app::get('site')->_('标题').'</span></td>
            <td width="40"><span class="dhb_edit">'.app::get('site')->_('编辑').'</span></td>
            <td width="40"><span class="dhb_del">'.app::get('site')->_('删除').'</span></td>
            </tr>
            </table>
            </div>
            </div>

            <div id="drag_ghost_box" class="drag_ghost_box" style="visibility:hidden">
            </div>';
    }


}//End Class
