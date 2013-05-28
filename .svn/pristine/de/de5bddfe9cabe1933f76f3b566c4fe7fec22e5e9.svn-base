<?php
function widget_event(&$setting,&$smarty) {
    $limit       = (intval($setting['limit'])>0)?intval($setting['limit']) : 6;
    $objEvent = app::get('yunmall')->model('event');

    $setting['max_length']=$setting['max_length']?$setting['max_length'] : 36;

    $orderby = in_array($objEvent->orderSelect(),$setting['orderby'])? $setting["orderby"] : null;
    $arrResult = $objEvent->getList('*',array(),0,$limit,$orderby);

    return $arrResult;
}
