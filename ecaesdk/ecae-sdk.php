<?php
@ini_set("display_errors",true);

define("ECAESDK_ROOT_URL","http://127.0.0.1/working/ecae");
require_once(ROOT_DIR."/ecaesdk/lib/ego.php");

// == 常量定义 ===============================
define("ECAE_SITE_ID","2100");
define("ECAE_SITE_NAME","ecaesdk");

// 数据库
define("ECAE_MYSQL_HOST_M","localhost");  // mysql连接地址
define("ECAE_MYSQL_HOST_S",constant("ECAE_MYSQL_HOST_M"));
define("ECAE_MYSQL_USER","root");    // mysql帐号
define("ECAE_MYSQL_PASS","");        // mysql密码
define("ECAE_MYSQL_DB","ecae_test"); // 要使用的库 

// 库载入
require_once(dirname(__FILE__)."/lib/include.php");
