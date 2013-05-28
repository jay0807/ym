<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
 

$db['user'] = array (
    'columns' =>
    array (
        'id' =>array (
            'type' => 'int',
            'required' => true,
            'label'=> app::get('yunmall')->_('自增id'),
            'pkey' => true,
            'extra' => 'auto_increment',
            'width' => 10,
            'editable' => false,
            'in_list' => true,
        ),
        'taobao_id' =>array (
            'type' => 'int',
            'required' => true,
            'label'=> app::get('yunmall')->_('淘宝ID'),
            'editable' => false,
            'width' => 30,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'is_title'=>true,
        ),
        'taobao_nick' =>array (
            'type' => 'varchar(255)',
            'label'=> app::get('yunmall')->_('淘宝昵称'),
            'editable' => false,
            'width' => 30,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 1,
        ), 
        'pkg_type' =>array (
            'type' => 'varchar(20)',
            'label'=> app::get('yunmall')->_('套餐类型'),
            'width' => 30,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 2,
        ),
        'create_time' =>array (
            'type' => 'time',
            'label'=> app::get('yunmall')->_('注册时间'),
            'width' => 150,
            'filtertype' => 'time',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 8,
        ),
        'create_ip' =>array (
            'type' => 'char(15)',
            'label'=> app::get('yunmall')->_('注册IP'),
            'in_list' => true,
        ),
        'login_time' =>array (
            'type' => 'time',
            'label'=> app::get('yunmall')->_('登陆时间'),
            'width' => 150,
            'filtertype' => 'time',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true, 
        ),
        'login_ip' =>array (
            'type' => 'char(15)',
            'label'=> app::get('yunmall')->_('登陆IP'),
            'in_list' => true,
        ),
        'access_expire' =>array (
            'type' => 'time',
            'label'=> app::get('yunmall')->_('access_token访问时限'),
            'editable' => false,
        ),
        'access_token' =>array (
            'type' => 'varchar(100)',
            'label'=> app::get('yunmall')->_('access_token'),
            'editable' => false,
        ),
        'refresh_expire' =>array (
            'type' => 'time',
            'label'=> app::get('yunmall')->_('refresh_token访问时限'),
            'editable' => false,
        ),
        'refresh_token' =>array (
            'type' => 'varchar(100)',
            'label'=> app::get('yunmall')->_('refresh_token'),
            'editable' => false,
        ),
        'ent_name' =>array (
            'type' => 'varchar(255)',
            'label'=> app::get('yunmall')->_('企业名称'),
            'editable' => false,
            'width' => 80,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 3,
        ),
        'ent_url' =>array (
            'type' => 'varchar(100)',
            'label'=> app::get('yunmall')->_('商城URL'),
            'editable' => false,
            'width' => 200,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 5,
        ),
        'contact' =>array (
            'type' => 'varchar(50)',
            'label'=> app::get('yunmall')->_('联系人'),
            'width' => 30,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 6,
        ),
        'email' =>array (
            'type' => 'varchar(255)',
            'label'=> app::get('yunmall')->_('email'),
            'width' => 100,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
        ),
        'phone' =>array (
            'type' => 'varchar(20)',
            'label'=> app::get('yunmall')->_('手机号'),
            'width' => 30,
            'searchtype' => 'has',
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
            'default_in_list' => true,
            'order' => 7,
        ),
        'recordno' =>array (
            'type' => 'varchar(50)',
            'label'=> app::get('yunmall')->_('备案号'),
            'width' => 30,
            'filtertype' => 'has',
            'filterdefault' => true,
            'in_list' => true,
        ),
        'site' =>array (
            'type' => 'varchar(50)',
            'label'=> app::get('yunmall')->_("开通站点"),
            'in_list' => true,
            'default_in_list' => true,
            'order' => 4,
        )
  ),
  'comment' => app::get('yunmall')->_('用户表'),
  'index' => 
      array (
        'ind_taobao_nick' => 
        array (
          'columns' => 
          array (
            0 => 'taobao_nick',
          ),
          'prefix' => 'unique',
        ),
        'ind_taobao_id' => 
        array (
          'columns' => 
          array (
            0 => 'taobao_id',
          ),
          'prefix' => 'unique',
        ),
  ),
  'version' => '$Rev$',
);
