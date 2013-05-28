<?php
/**
 * ShopEx licence
 *
 * @copyright  Copyright (c) 2005-2010 ShopEx Technologies Inc. (http://www.shopex.cn)
 * @license  http://ecos.shopex.cn/ ShopEx License
 */
/*
 * @package base
 * @copyright Copyright (c) 2010, shopex. inc
 * @author edwin.lzh@gmail.com
 * @license 
 */
class base_kvstore_tokyotyrant extends base_kvstore_abstract implements base_interface_kvstore_base 
{

    static private $_cacheObj;
    static private $_persistent;

    function __construct($prefix) 
    {
        $this->connect();
        $this->prefix = $prefix;
    }//End Function

    public function connect() 
    {
        if(!isset(self::$_cacheObj)){
            if(defined('KVSTORE_MEMCACHE_CONFIG') && constant('KVSTORE_MEMCACHE_CONFIG')){
                self::$_cacheObj = new Memcache;
                $config = explode(',', KVSTORE_MEMCACHE_CONFIG);
                $persistent = self::$_persistent === false ? false : true;
                foreach($config AS $row){
                    $row = trim($row);
                    if(strpos($row, 'unix:///') === 0){
                        continue;   //暂不支持
                    }else{
                        $tmp = explode(':', $row);
                        self::$_cacheObj->addServer($tmp[0], $tmp[1], $persistent);
                    }
                }
            }else{
                trigger_error('can\'t load KVSTORE_MEMCACHE_CONFIG, please check it', E_USER_ERROR);
            }
            //检查是否有可用kv服务器
            $status = self::$_cacheObj->getExtendedStats();
            if( !$status || !is_array($status) ) {
                trigger_error('can\'t connect to kv-storage system', E_USER_ERROR);
            }
            foreach($status as $key => $value)
            {
                if($value === false) {
                    unset($status[$key]);
                }
            }
            if(count($status) == 0){
                trigger_error('can\'t connect to kv-storage system', E_USER_ERROR);
            }
        }
    }//End Function

    public function fetch($key, &$value, $timeout_version=null) 
    {
        $data = self::$_cacheObj->get($this->create_key($key));
        if($data !== false){
            $store = unserialize($data);    //todo：反序列化
            if($timeout_version < $store['dateline']){
                if($store['ttl'] > 0 && ($store['dateline']+$store['ttl']) < time()){
                    return false;
                }
                $value = $store['value'];
                return true;
            }
        }
        return false;
    }//End Function

    public function store($key, $value, $ttl=0) 
    {
        $store['value'] = $value;
        $store['dateline'] = time();
        $store['ttl'] = $ttl;
        return self::$_cacheObj->set($this->create_key($key), serialize($store), 0, 0);  //todo：不压缩，序列化
    }//End Function

    public function delete($key) 
    {
        return self::$_cacheObj->delete($this->create_key($key));
    }//End Function

    public function recovery($record) 
    {
        $key = $record['key'];
        $store['value'] = $record['value'];
        $store['dateline'] = $record['dateline'];
        $store['ttl'] = $record['ttl'];
        return self::$_cacheObj->set($this->create_key($key), serialize($store), 0, 0);  //todo：不压缩，序列化
    }//End Function

    /**
    * 设置TT持久化连接
    * @access public
    * @param bool $persistent true持久化 false非持久化
    * @return bool
    */
    static function setPersistentConn($persistent=true){
        self::$_persistent = $persistent;
    }

    /**
    * 关闭TT对象句柄
    * @access public
    * @return bool
    */
    public function close(){
        if (isset(self::$_cacheObj) && self::$_cacheObj && self::$_cacheObj instanceof Memcache){
            self::$_cacheObj->close();
            self::$_cacheObj = NULL;
            return true;
        }
        return false;
    }

}//End Class
