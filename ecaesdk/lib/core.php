<?php
/**
 * ECAE SDK 模拟ecae环境 相关的基础类库
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-25 00:40
 */
define("ECAESDK_DATA_DIR",dirname(__FILE__)."/../data/".ECAE_SITE_ID."/");

// 数据本地存储
class ecaesdk_storage {

    public $header = '<?php exit(); ?>';
    static private $instance = array();

    private function __construct($prefix) {
        $this->prefix= $prefix;
        $this->header_length = strlen($this->header);
    }//End Function

    static public function getInstance($prefix) {
        if(!isset(self::$instance[$prefix]) || !self::$instance[$prefix]) {
            self::$instance[$prefix] = new ecaesdk_storage($prefix);
        }
        return self::$instance[$prefix]; 
    }

    public function store($key, $value, $ttl=0) {
        $this->check_dir();
        $data = array();
        $data['value'] = $value;
        $data['ttl'] = $ttl;
        $data['dateline'] = time();
        $org_file = $this->get_store_file($key);
        $tmp_file = $org_file . '.' . str_replace(' ', '.', microtime()) . '.' . mt_rand();
        if(file_put_contents($tmp_file, $this->header.serialize($data))){
            if(copy($tmp_file, $org_file)){
                @unlink($tmp_file);
                return true;
            }
        }
        return false;
    }//End Function

    public function fetch($key, &$value, $timeout_version=null) {
        $file = $this->get_store_file($key);
        if(file_exists($file)){
            $data = unserialize(substr(file_get_contents($file),$this->header_length));
            if(!isset($data['dateline']))   $data['dateline'] = @filemtime($file);  //todo:兼容老版本
            if($timeout_version < $data['dateline']){
                if(isset($data['expire'])){
                    if($data['expire'] == 0 || $data['expire'] >= time()){
                        $value = $data['value'];
                        return true;
                    }
                    return false;
                    //todo:兼容老版本
                }else{
                    if($data['ttl'] > 0 && ($data['dateline']+$data['ttl']) < time()){
                        return false;
                    }
                    $value = $data['value'];
                    return true;
                }
            }
        }
        return false;
    }//End Function

    public function delete($key) {
        $file = $this->get_store_file($key);
        if(file_exists($file)){
            return @unlink($file);
        }
        return false;
    }//End Function
    
    private function check_dir() {
        if(!is_dir(ECAESDK_DATA_DIR.'/'.$this->prefix)) {
            ecaesdk_utils::mkdir_p(ECAESDK_DATA_DIR.'/'.$this->prefix);
        }
    }//End Function

    private function get_store_file($key) {
        return ECAESDK_DATA_DIR.'/'.$this->prefix.'/'.$this->create_key($key).'.php';
    }//End Function

    public function create_key($key) {
        return md5($this->prefix . $key);
    }//End Function    

}//End Class ecaesdk_storage

// 文件存储
class ecaesdk_file {
    static private $instance = array();
    private $bucket = null;

    public function setBucket($bucket) {
        $this->bucket = $bucket;
        $this->data_dir = ECAESDK_DATA_DIR."/file/".$this->bucket;
    }

    static public function getInstance($prefix) {
        if(!isset(self::$instance[$prefix]) || !self::$instance[$prefix]) {
            self::$instance[$prefix] = new ecaesdk_file();
        }
        return self::$instance[$prefix]; 
    }

    private function check_dir($path = null) {
        if(!in_array($this->bucket,array(constant("ECAE_SITE_NAME")."-public",constant("ECAE_SITE_NAME")."-private",constant("ECAE_SITE_NAME")."-images"))) {
            if(!is_dir($this->data_dir)) throw new Exception("bad bucket");
        }
        if(!is_dir($this->data_dir."/".$path)) {
            ecaesdk_utils::mkdir_p($this->data_dir."/".$path);
        }
    }//End Function

    public function store($bucket,$local_file,$options) {
        $this->setBucket($bucket);
        $path = isset($options["path"])? $options["path"] : "";
        $name = isset($options["name"])? $options["name"] : basename($local_file);
        $this->check_dir($path);
        $filename = ($path)?  $path."/".$name : $name;
        @copy($local_file,$this->data_dir."/".$path."/".$name);
        return $this->bucket.":".$filename; 
    }

    public function replace($file_id,$local_file) {
        list($bucket,$filename) = explode(":",$file_id);
        $options = array(
            "path"=>dirname($filename),
            "name"=>basename($filename),
        );
        return $this->store($bucket,$local_file,$options);
    }

    public function fetch($file_id) {
        return file_get_contents($this->_getRealPath($file_id));
    }

    private function _getRealPath($file_id) {
        list($bucket,$filename) = explode(":",$file_id);
        $this->setbucket($bucket);
        return $this->data_dir."/".$filename;
    }

    public function getUrl($file_id) {
        list($bucket,$filename) = explode(":",$file_id);
        return constant("ECAESDK_ROOT_URL")."/ecaesdk/data/".ECAE_SITE_ID."/file/".$bucket."/".$filename;
    }

    public function delete($file_id) {
        return @unlink($this->_getRealPath($file_id));
    }

    public function getBucketList() {
        $dir = ECAESDK_DATA_DIR."/file/";
        return array();
    }
} // end class ecaesdk_file

// 常用类
class ecaesdk_utils {
    static public function xorEncode($string,$key){
       $encoding="";
       for($i=0;$i<strlen($string);$i++){
           $encoding=$encoding.($string[$i]^$key);
       }
       return $encoding;
    }

    static public function xorDecode($string,$key){
       $decoding="";
       for($i=0;$i<strlen($string);$i++){
           $decoding=$decoding.($string[$i]^$key);
       }
       return $decoding;
    }

    // 创建目录
    static public function mkdir_p($dir,$dirmode=0755){
        $path = explode('/',str_replace('\\','/',$dir));
        $depth = count($path);
        for($i=$depth;$i>0;$i--){
            if(file_exists(implode('/',array_slice($path,0,$i)))){
                break;
            }
        }
        for($i;$i<$depth;$i++){
            if($d= implode('/',array_slice($path,0,$i+1))){
                if(!is_dir($d)) mkdir($d,$dirmode);
            }
        }
        return is_dir($dir);
    }

}// end class ecaesdk_utils
