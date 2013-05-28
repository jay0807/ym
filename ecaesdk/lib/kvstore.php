<?php
/**
 * ECAE SDK 模拟ecae kvstore的存储
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-22 01:42
 * @update: 2012-02-25 00:54
 */

/**
 * ecae 写KV
 *
 * @param string $key
 * @param string $value
 * @return boolean
 */
define("ECAE_KVDB_ENABLE",true);
function ecae_kvstore_write($key,$value) {
    return ecaesdk_storage::getInstance("kvstore")->store($key,$value);
}

/**
 * ecae 读KV
 *
 * @param string $key
 * @param string &$value 引用返回
 * @return boolean
 */
function ecae_kvstore_read($key,&$value) {
    return ecaesdk_storage::getInstance("kvstore")->fetch($key,$value); 
}

/**
 * ecae 删除指定KV
 *
 * @param string $key
 * @return boolean
 */
function ecae_kvstore_delete($key) {
    return ecaesdk_storage::getInstance("kvstore")->delete($key); 
}
