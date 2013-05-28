<?php
require_once(dirname(__FILE__)."/../ecae-sdk.php");

// 取了一个空的key
var_dump(ecae_cache_read("test")); // false

// 写cache
var_dump(ecae_cache_write("test","12345")); // true
var_dump(ecae_cache_read("test")); // 12345

// cache状态 这个方法可以无视
var_dump(ecae_cache_stats());  // array
