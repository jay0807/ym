<?php
function widget_resource(&$setting,&$smarty) {
    $limit       = (intval($setting['limit'])>0)?intval($setting['limit']) : 6;
    $objResource = app::get('yunmall')->model('resource');

    $setting['max_length']=$setting['max_length']?$setting['max_length'] : 36;

    $orderby = in_array($objResource->orderSelect(),$setting['orderby'])? $setting["orderby"] : null;
    $arrResult = $objResource->getList('*',array(),0,$limit,$orderby);

    return $arrResult;
}
