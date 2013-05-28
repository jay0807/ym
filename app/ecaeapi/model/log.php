  <?php
/**
 * 
 **/
class ecaeapi_mdl_log
{
    private $log_request_url;

    public function __construct( $app )
    {
        $this->app = $app;
        $this->log_request_url = 'http://'.LOG_SYS_HOST.'/{site}/{sha1}/{type}/{level}/?ecae{search}{rows}{start}{date}';
        //$this->log_request_url = 'http://'.LOG_SYS_HOST.'/solr/'.LOG_SYS_PREFIX.'_{site}_{type}_{sha1}/select?q={filter}&presort=key{start}{rows}{sort}'; #日志地址
        #$this->log_request_url = 'http://192.168.65.215:8098/solr/test31_2113_{type}_{sha1}/select?q={filter}&presort=key{start}{rows}{sort}'; #日志地址
    }

  
    public function getList($filter=array(),$offset=null,$limit=null,$order=null)
    {
        $type = $filter['type'];
        #$site = $filter['site'] = 2106;
        $site = $filter['site'];

        #if(!$filter['sha1'])
            $sha1 = $filter['sha1'];# = 'a381562afa0e16c5548e829b2f1a659a8c2e7c11';
        #else $sha1 = $filter['sha1'];
        
        /*
        unset($filter['type']);
        unset($filter['sha1']);
        unset($filter['site']);
        
        if($filter){
            $q = array();
            foreach( $filter as $k => $v) {
                if(is_array($v)){
                    $q[] = $k.':['. implode(' TO ',$v) .']';
                } else {
                    $q[] = $k .':'.$v;
                }
            }
            $filter = implode(' AND ',$q);
        }
        */

        $level = $filter['level_txt'];

        if($filter['log_txt']){
            $search = '&filter='.preg_replace("/[^A-Za-z0-9-_]/isU", "", $filter['log_txt']);
        }
        
        if($offset!=null)
            $start = '&pos='.$offset;

        if($limit!==null)
            $rows = '&limit='.$limit;

        if($filter['d_date']){
            $date = '&date='.date("Y-m-d", $filter['d_date'][0]);  
        }else{
            $date = '&date='.date("Y-m-d", time());
        }

        if($type == 'ecae'){
            $date = "";
        }// 管理日志读取所有
        
        /*
        if($order!==null)
            $sort = '&sort='.$order;
        
        #返回值类型
        $wt = '&wt=json';
        $filter = urlencode($filter);
        */

        #日志地址
        $url = str_replace(
                array('{site}','{type}','{level}','{sha1}','{start}','{rows}','{search}','{date}'),
                array($site,$type,$level,$sha1,$start,$rows,$search,$date),
                $this->log_request_url
            );
        //$url .= $wt;
        //echo $url;exit;
        //echo $url;
        //$re = file_get_contents($url);
        //$url = 'http://www.baidu.com';
        if($type) {
            $re = kernel::single('base_httpclient')->get($url);
            if(strpos($re, "No such file or directory"))    $re = '';
            //var_dump($re);exit;
            //$re = json_decode($re);
            return $this->parse_data($re, $type, $level);
        }
        return false;
    }


    private function parse_data($data,$type,$level)
    {
        /*
        $response = $data->response;
        $response_header = $data->responseHeader;
        if(is_array($response->docs)) {
            foreach($response->docs as $row){
                $log_txt = trim($row->fields->log_txt);
                $p = strpos($log_txt,' ');
                $user = substr($log_txt,0,$p);
                $log = trim(substr($log_txt,$p));
                $this->logLang($log,$log_result);
                $tmp = array(
                    'date' => $this->get_date($row->fields->d_date),
                    'level_txt' => $row->fields->level_txt,
                    'id' => $row->id,
                    'user' => $this->format_user($user),
                    'log' => $log,
                    'log_result'=>$log_result,
                    'log_txt' => $log_txt,
                    );
                $arr['data'][] = $tmp;
            }
            $arr['info'] = (array)$response_header->params;
            $arr['info']['status'] = $response_header->status;
            $arr['info']['qtime'] = $response_header->qtime;
        }
        return $arr;
        */
        $data = trim($data);
        if(empty($data))    return $arr;
        $rows = explode("\n", $data);
        foreach($rows AS $row){
            $datas = explode("\t", trim($row));
            $id = array_shift($datas);
            $data = implode(" ", $datas);
            if($type == 'ecae'){
                $tmprows = explode(" ", trim($data));
                //$date = $this->get_date(strtotime(array_shift($tmprows)));
                $date = strtotime(array_shift($tmprows));
                $log_txt = trim(join(" ", $tmprows));
                $p = strpos($log_txt,' ');
                $user = $this->format_user(substr($log_txt,0,$p));
                $log = trim(substr($log_txt,$p));
                $this->logLang($log,$log_result);
            }else{
                $log_txt = trim($data);
            }
            $tmp = array(
                'date' => $date,
                'level_txt' => $level,
                'id' => $id,
                'user' => $user,
                'log' => $log,
                'log_result'=>$log_result,
                'log_txt' => $log_txt,
                );
            $arr['data'][] = $tmp;
            if(!isset($arr['info']['pre'])) $arr['info']['pre'] = $id;
            $arr['info']['next'] = $id - 1;  //最后一个
        }
        //echo '<PRE>';
        //print_r($arr['data']);exit;
        return $arr;
    }


    private function logLang(&$log,&$log_result){
        $lang = array(
            'addon_active' => '开通服务',
            'addon_acitve' => '开通服务',
            'active'=>'应用全局操作',
            'storage_register_group' => '创建存储点',
            'storage_register_grop' => '创建存储点',
            'add_user_to_site' => '添加用户',
            'create'=>'创建应用',
            'add_alias'=>'添加别名',
            'publish' => '更新发布点',
            'update_publish_version'=>'更新发布版本',
            'deploy'=>'部署应用服务器信息',
            'be_product'=>'站点添加为产品',
            'verify_domain'=>'域名审核',
        );
        
        #$d = array('deploy');
        $record = array(
            'storage_register_group' => '',
            'add_user_to_site' => array('pattern'=>'/\{\{(.*?),"?([^"]*)"?.*\},?(.*?),.*/','replacement'=>'用户:$2,类型:$3  '),
            'create'=>'',
            'publish' => array('pattern'=>'/\[?\{\{"(\w*?)","(\w*?)"\},([^}]*?)\}\]?/','replacement'=>"  路径:$1, 版本:$2, 概率:$3 "),
            'update_publish_version'=>'',
            'active'=>array('pattern'=>array('/true/','/false/'),'replacement'=>array('开启访问','关闭访问')),
            'deploy'=>array('pattern'=>array('/.*,add,(\d+),ok.*/i','/.*,delete,(\d+),ok.*/i','/.*,set,(\d+),ok.*/i'),
                            'replacement'=>array('添加了$1个进程','删除了$1个进程','设置了$1个进程')),
            'be_product'=>'',
            'add_alias'=>array('pattern'=>array('/\{["|\']?(.*?)["|\']?,ok\}/','/\{["|\']?(.*?)["|\']?,.*\}/'),
                            'replacement'=>array('域名:$1,状态：成功','域名:$1,状态：失败')),
            'verify_domain'=>array('pattern'=>'/\{"([^"]*)",([^\}]*)\}/','replacement'=>'域名:$1   状态:$2'),
        );

        list($key,$status) = explode('=',$log);
        $v = null;
        $key = trim($key,'{}');
        if(strpos($key,',')!==false)
            list($k,$v) = explode(',',$key);
        else $k = $key;

        if(!isset($lang[$k]) || !$lang[$k]){
            if(null===array_search($k,$d))
                $result = array($key,$status);
        } else {
            if($status=='ok'){
                $status = '成功';
            }elseif(isset($record[$k])){
                $re = $record[$k];
                if($re && is_array($re))
                    $status = preg_replace($re['pattern'],$re['replacement'],$status);
            }elseif(strpos($status,'{badmatch')!==false){
                $status = '失败';
            }
            if($v) $v = '  '.$v;
            $result = array($lang[$k].$v,$status);
        }
        $log = $result[0];
        $log_result = $result[1];
    }

    function get_date($time){
        return $time + date("Z");
    }

    function format_user($user){
        if($user=='undefined'){
            return "";
        }elseif(!$user){
            return "";
        }else{
            return preg_replace("/.*?,.*?([a-z0-9_]+).*?}(.*?:[^\']*)?.*/is","$1",$user);
        }
    }

}
