<?php
class partner_log {

    public function __construct($app) {
        $this->app = $app;
    }

    public function getEcaeLog($site_id,$offset = "",$limit = 10) {
        // 获取数字ID
        $arrFilter = array();
        $arrSite = kernel::single("ecaeapi_site")->get_info($site_id);
        $arrFilter['site_id'] = $arrSite['id'];
        $arrFilter['search_type'] = "ecae";
        $arrFilter['type']        = "info";
        $objLog = $this->app->model('log');

        $arrLog = $this->app->model('log')->getList("*",$arrFilter,$offset,$limit);

        if($arrLog) {
            $arrResult = array(
                "data" => $this->_parse_log($arrLog['data']), 
                "page" => $arrLog['page'],
            );
        } else {
            $arrResult = array(
                "data" => false, 
                "page" => false,
            );
        }
        return $arrResult;
    } // end function getECAELog

    public function _parse_log($arrData) {
        $arrResult = array();
        foreach($arrData as $row){
            $log_txt = trim($row['log_txt']);
            $p = strpos($log_txt,' ');
            $user = $this->_log_format_user(substr($log_txt,0,$p));
            $arrTemp = explode(":",$user);
            $user = (count($arrTemp) == 1)? $arrTemp[0] : $arrTemp[1];
            $log = trim(substr($log_txt,$p));
            $this->_log_lang($log,$log_result);

            $arrResult[] = array(
                'date'       => $this->_log_date($row['d_date']),
                'level_txt'  => $row['level_txt'],
                'id'         => $row['id'],
                'user'       => $user,
                'log'        => $log,
                'log_result' => $log_result,
                'log_txt'    => $log_txt,
            );
        }
        return $arrResult;
    } // end function _parse_log


    private function _log_lang(&$log,&$log_result){
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
            'active'=>array('pattern'=>array('/true/','/false/','/delete/'),'replacement'=>array('开启访问','关闭访问','删除站点')),
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
    } // end function _log_lang

    private function _log_date($time){
        return $time + date("Z");
    } // end function _log_date

    private function _log_format_user($user){
        if($user=='undefined'){
            return "";
        }elseif(!$user){
            return "";
        }else{
            return preg_replace("/.*?,.*?([a-z0-9_:]+).*?}(.*?:[^\']*)?.*/is","$1",$user);
        }
    } // end function _log_format_user
	
} // end class
