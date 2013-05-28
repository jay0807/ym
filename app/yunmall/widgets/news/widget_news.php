<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */

function widget_news(&$setting,&$render){
    $limit     = (intval($setting['limit'])>0)?intval($setting['limit']) : 6;
    $objNotice = app::get('yunmall')->model('notice');

    $setting['max_length']=$setting['max_length']?$setting['max_length'] : 36;

    $orderby = in_array($objNotice->orderSelect(),$setting['orderby'])? $setting["orderby"] : null;
    $arrResult = $objNotice->getList('*',array(),0,$limit,$orderby);
    return $arrResult;
}
