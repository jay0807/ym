<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

class site_view_helper 
{

    public function function_header($params, &$smarty) 
    {
        $app_exclusion = app::get('base')->getConf('system.main_app');
        $html = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <meta name="generator" content="ecos.'.$app_exclusion['app_id'].'" />';
        $headers = $smarty->pagedata['headers']; 
        if(is_array($headers)){
            foreach($headers AS $header){
                $html .= $header . "\n";
            }
        }else{
            $html .= $headers . "\n";
        }
        $services = kernel::servicelist("site_view_helper");
        foreach($services AS $service){
            if(method_exists($service, 'function_header'))
                $html .= $service->function_header($params, $smarty);
        }
        return $html . '<style id="site_widgets_style"><%site_widgets_css%></style><script type="text/javascript">(function(){var widgets_style = document.getElementById(\'site_widgets_style\');var head = document.getElementsByTagName(\'head\')[0];head.appendChild(widgets_style);})();</script>';
    }//End Function

    public function function_footer($params, &$smarty) 
    {
        $footers = $smarty->pagedata['footers']; 
        if(is_array($footers)){
            foreach($footers AS $footer){
                $html .= $footer;
            }
        }else{
            $html .= $footers;
        }
        $services = kernel::servicelist("site_view_helper");
        foreach($services AS $service){
            if(method_exists($service, 'function_footer'))
                $html .= $service->function_footer($params, $smarty);
        }
        
        $html .= app::get('site')->getConf('system.foot_edit');

        $obj = kernel::service('site_footer_copyright');
        if(is_object($obj) && method_exists($obj, 'get')){
            $html .= $obj->get();
        }else{
            //$html .= base64_decode('PGRpdiBzdHlsZT0iY29sb3I6IzMzMztmb250LWZhbWlseTpWZXJkYW5hO2ZvbnQtc2l6ZToxMXB4O2xpbmUtaGVpZ2h0OjIwcHghaW1wb3J0YW50O292ZXJmbG93OnZpc2libGUhaW1wb3J0YW50O2Rpc3BsYXk6YmxvY2shaW1wb3J0YW50O3Zpc2liaWxpdHk6dmlzaWJsZSFpbXBvcnRhbnQ7cG9zaXRpb246cmVsYXRpdmU7ei1JbmRleDo2NTUzNSFpbXBvcnRhbnQ7dGV4dC1hbGlnbjpjZW50ZXI7Ij4KUG93ZXJlZCBCeSA8YSBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOm5vbmUiIGhyZWY9Imh0dHA6Ly93d3cuc2hvcGV4LmNuIiB0YXJnZXQ9Il9ibGFuayI+PGIgc3R5bGU9ImNvbG9yOiByZ2IoOTIsIDExMywgMTU4KTsiPlNob3A8L2I+PGIgc3R5bGU9ImNvbG9yOiByZ2IoMjQzLCAxNDQsIDApOyI+RXg8L2I+PC9hPiAKPC9kaXY+'); 
        }
        $icp = app::get('site')->getConf('system.site_icp');
        if( $icp )
            $html .= '<div style="text-align: center;">'.$icp.'</div>';
        
        return $html;
    }//End Function

    public function function_template_filter($params, &$smarty) 
    {
        
        if($params['type']){
            $render = kernel::single('base_render');
            $theme = kernel::single('site_theme_base')->get_default();
            $obj = kernel::single('site_theme_tmpl');
            $theme_list = $obj->get_edit_list($theme);
            $render->pagedata['list'] = $theme_list[$params['type']];
            unset($params['type']);
            $render->pagedata['selected'] = $params['selected'];
            unset($params['selected']);
            if(is_array($params)){
                foreach($params AS $k=>$v){
                    $ext .= sprintf(' %s="%s"', $k, $v);
                }
            }
            $render->pagedata['ext'] = $ext;
            return $render->fetch('admin/theme/tmpl/template_filter.html', app::get('site')->app_id);
        }else{
            return '';
        }
    }//End Function

}//End Class
