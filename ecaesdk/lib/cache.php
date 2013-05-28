<?php
/**
 * ECAE SDK 模拟ecae memcache的存储
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-22 01:42
 * @update: 2012-02-25 01:11
 */

/**
 * ecae 写缓存
 *
 * @param string $key
 * @param string $value
 * @return boolean
 */
define("ECAE_MEMCACHE_HOST","127.0.0.1:11211");
function ecae_cache_write($key,$value) {
    return ecaesdk_storage::getInstance("cache")->store($key,$value);
}

/**
 * ecae 读缓存
 *
 * @param string $key
 * @return string
 */
function ecae_cache_read($key) {
    ecaesdk_storage::getInstance("cache")->fetch($key,$value);
    if(null == $value) return false;
    return $value;
}

/**
 * ecae 缓存相关信息(存储量,命中率)
 *
 * @return array
 */
function ecae_cache_stats() {
    return array(
        '127.0.0.1:11011'=>array(
            "pid"=>"15380",
            "uptime"=>"81155",
            "time"=>"1318997580",
            "version"=>"1.4.6",
            "libevent"=>"1.4.13-stable",
            "pointer_size"=>"64",
            "rusage_user"=>"0.007998",
            "rusage_system"=>"0.011998",
            "curr_connections"=>"10",
            "total_connections"=>"113",
            "connection_structures"=>"19",
            "cmd_get"=>"21",
            "cmd_set"=>"7",
            "cmd_flush"=>"0",
            "get_hits"=>"11",
            "get_misses"=>"10",
            "delete_misses"=>"0",
            "delete_hits"=>"0",
            "incr_misses"=>"0",
            "incr_hits"=>"0",
            "decr_misses"=>"0",
            "decr_hits"=>"0",
            "cas_misses"=>"0",
            "cas_hits"=>"0",
            "cas_badval"=>"0",
            "auth_cmds"=>"0",
            "auth_errors"=>"0",
            "bytes_read"=>"4031",
            "bytes_written"=>"63567",
            "limit_maxbytes"=>"16777216",
            "accepting_conns"=>"1",
            "listen_disabled_num"=>"0",
            "threads"=>"4",
            "conn_yields"=>"0",
            "bytes"=>"70",
            "curr_items"=>"1",
            "total_items"=>"7",
            "evictions"=>"0",
            "reclaimed"=>"0",
        ),
    );
}
