<?php
require_once(dirname(__FILE__)."/../ecae-sdk.php");

// 取了一个空的key
var_dump(ecae_kvstore_read("test",$value)); // false
var_dump($value); // NULL

// 写kv
var_dump(ecae_kvstore_write("test","12345")); // true
var_dump(ecae_kvstore_read("test",$value)); // true
var_dump($value); // 12345

// 删除
var_dump(ecae_kvstore_delete("test"));  // true
var_dump(ecae_kvstore_read("test",$value1)); // false
var_dump($value1); // NULL
