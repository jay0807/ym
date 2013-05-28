<?php
@ini_set("display_errors",true);
require_once(dirname(__FILE__)."/../ecae-sdk.php");

// 保存一个文件
$file_id = ecae_file_save(constant("ECAE_SITE_NAME")."-public",__FILE__); 
var_dump($file_id); // string(16) "public:/file.php"

// 获取外网访问地址
var_dump(ecae_file_url($file_id));


// 获取
var_dump(ecae_file_fetch($file_id,"/tmp/test_ecae_file.log")); 
var_dump(file_get_contents("/tmp/test_ecae_file.log")); // 本文件的内容

// 替换文件 替换成cache文件
var_dump(ecae_file_replace($file_id,dirname(__FILE__)."/cache.php"));

// 获取
var_dump(ecae_file_fetch($file_id,"/tmp/test_ecae_file.log")); 
var_dump(file_get_contents("/tmp/test_ecae_file.log")); // cache.php文件内容

// 删除文件
var_dump(ecae_file_delete($file_id));


