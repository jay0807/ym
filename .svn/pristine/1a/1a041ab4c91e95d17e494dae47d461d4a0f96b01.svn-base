<?php
/**
 * 
 **/
class ecaeapi_mdl_mysql
{


    public function info( $site_id,$user_id )
    {
        $who_is = app::get('ecaeapi')->model('site')->who_is($site_id,$user_id);
        if($who_is == 'admin' || $who_is=='committer'){
            $env = app::get('ecaeapi')->model('site')->env($site_id); 
            if(!isset($env["ECAE_MYSQL_HOST_M"])) return false;
            
            return array(
                    'host_s' => $env["ECAE_MYSQL_HOST_S"],
                    'host_m' => $env["ECAE_MYSQL_HOST_M"],
                    'port'   => $env["ECAE_MYSQL_PORT"],
                    'user'   => $env["ECAE_MYSQL_USER"],
                    'passwd' => $env["ECAE_MYSQL_PASS"],
                    'db'     => $env["ECAE_MYSQL_DB"],
            );
        }else{
            return false;
        }
    }


    public function table_status($site_id,$user_id)
    {
        $info = $this->info( $site_id,$user_id );
        #list($host,$port) = explode(':',$info['host_m']);
        $lnk = mysql_connect($info['host_m'],$info['user'],$info['passwd']);
        mysql_select_db($info['db'],$lnk);
        $rs = mysql_query("show table status",$lnk);

        $record = array(
                'engine' => array('title'=>'存储引擎'),
                'rows' => array('title'=>'记录数'),
                'data_length' => array('title'=>'占用空间'),
                'create_time' => array('title'=>'创建时间'),
                'update_time' => array('title'=>'更新时间'),
                'collation' => array('title'=>'字符集'),
            );

        while($r = mysql_fetch_assoc($rs)) {
            $arr = array();
            foreach($r as $k => $v){
                $key = strtolower($k);
                if(!isset($record[$key]))
                    continue;
                $arr[$k]= array(
                    'value' => $v,
                    'title' => $record[$key]['title'],
                    );
            }
            $tables[$r['Name']] = $arr;
        }
        return $tables;
    }


}
