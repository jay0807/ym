<?php
/**
 * ECAE SDK 模拟ecae环境  将所有类库进行载入
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-25 01:30
 */
define("ECAE_SDK_LIB",dirname(__FILE__)."/");
function ecaesdk_include($module) {
    require_once(constant("ECAE_SDK_LIB").$module.".php");
}

// == core ===================================
ecaesdk_include("core");
// == cache ==================================
ecaesdk_include("cache");
// == kvstore ================================
ecaesdk_include("kvstore");
// == storage ================================
ecaesdk_include("elmar");
// == crypto ================================
ecaesdk_include("crypto");
// == agent api ================================
//@require_once(constant("ECAE_SDK_LIB")."elmar.php");
