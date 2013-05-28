<?php
require_once(dirname(__FILE__)."/../ecae-sdk.php");

// 只是SDK中只是将入参进行base64 再与站点ID异或 加解密还是以 ECAE环境为主 
// 这个主要为了模拟加解密的处理 能加密能解就ok了
// 初始化 使用Crypto的方法前先初始化
ecae_rsa_init(); 

// 加密
var_dump($decode = ecae_rsa_encrypt("12345"));

// 解密
var_dump(ecae_rsa_decrypt($decode));  // 12345
