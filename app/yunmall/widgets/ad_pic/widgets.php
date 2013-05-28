<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

$setting['author']='ShopEx';

$setting['version'] = '0.1';
$setting['order']   = 18;


$setting['name']=app::get('yunmall')->_('一张图片');

$setting['catalog']    = app::get('yunmall')->_('广告相关');

$setting['description']    = app::get('yunmall')->_('可自定义展示一张图片,并且可以为其加上连接');

$setting['usual']    = '1';

$setting['stime']='2013-3-4';
//,product,goods:act,
//$setting['scope']=array('');

$setting['template'] = array(
                            'default.html'=>app::get('yunmall')->_('默认')
                        );

?>
