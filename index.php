<?php
#include("app/serveradm/xhprof.php");
define('ROOT_DIR',realpath(dirname(__FILE__)));
if(!defined("ECAE_SITE_NAME")) {
    require_once(ROOT_DIR."/ecaesdk/ecae-sdk.php");
}

require(ROOT_DIR.'/app/base/kernel.php');
kernel::boot();
