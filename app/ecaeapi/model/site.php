<?php
/**
 * 
 **/
class ecaeapi_mdl_site
{
    public $msg;

    
    #site info(dump data)
    private $__sites = array();

    private $__runtime_attrs = array(
            'mysql.env.host',
        );

    public function __construct( $app )
    {
        $this->app = $app;
    }

  
    public function getList($uid)
    {
	$arr = kernel::single('ecaeapi_user')->getSiteList($uid);
	foreach($arr as $v) {
            $ret[$v['site']['id']] = $v;
        }
#        $data = $this->list_alias($uid);
#        foreach($data as $k => $v) {
#            $a = $this->get_all_info($v['name']['sitename']);
#            $a['default_domain'] = $this->default_domain($v['name']['sitename']);
#            $ret[$a['site']['id']] = $a;
#        }
        krsort($ret);
        return $ret;
    }

    public function dump($sid)
    {
        $data = $this->get_all_info($sid);
        return $data;
    }


    public function get_all_info($sid)
    {
        if($this->__sites[$sid]) return $this->__sites[$sid];

        $data = kernel::single('ecaeapi_site')->get_all_info($sid);
        
        if(!$data['alias'])return false;
        if(!$data['attr']['name']) $data['attr']['name']=$data['alias'][0]['name'][1];
        if(!$data['attr']['title']) $data['attr']['title']=$data['attr']['name'];
        $this->__sites[$sid] = $data;
        return $data;
    }


    public function count($filter=null)
    {
        $data = $this->getList( '*',$filter );
        return count($data);
    }

    public function delete($site_id,$secret){
        return kernel::single('ecaeapi_site')->delete($site_id,$secret);
    }

    //attr user_id 开通人
    function save(&$data)
    {
        $uid = $data['uid'];
        $options = $data['options'];
        $name = $options['name'];
        if($options['scm'])
            $sid = kernel::single('ecaeapi_site')->create($uid,$name,$options['scm']);
        else
            $sid = kernel::single('ecaeapi_site')->create($uid,$name);

        if($sid) {
            unset($options['name']);
            unset($options['scm']);
            $this->set_attr($name,'user_id',$uid);
            if($options) {
                foreach($options as $key => $val) {
                    $re = $this->set_attr($name,$key,$val);
                }
                return true;
            }
        }
        return $sid;
    }


    public function deploy_label($site_id)
    {
        $data = kernel::single('ecaeapi_site')->deploy_label($site_id);
        foreach($data as $k => $v){
            $arr[$k]['info'] = $v[0];
            $arr[$k]['ident'] = $v[1];
        }
        return $arr;
    }

    public function set_attr($name,$key,$val)
    {
        return kernel::single('ecaeapi_site')->set_attr($name,$key,$val);
    }

    public function list_alias($uid,$single=true)
    {
        $data = kernel::single('ecaeapi_site')->list_site_alias($uid);
        if( !$single ) return $data;
        if(!$data) return array();
        foreach( $data as $key =>  $row ) {
            if(!is_array($row)) continue;
            $tmp = $row[0]['name'];
            unset($row[0]['name']);
            $row[0]['name']['agentid'] = $tmp[0];
            $row[0]['name']['sitename'] = $tmp[1];
            $arr[$key] = $row[0];
        }
        return $arr;
    }


    public function default_domain($site){
        $ret = kernel::single('ecaeapi_site')->default_domain($site);
        return $ret;
    }

    function get_domain($site){
        return 'http://'.$this->default_domain($site).(ECAE_DOMAIN_PORT=='80'?'':':'.ECAE_DOMAIN_PORT);
    }

    public function list_domain($site_id)
    {
        $ret = kernel::single('ecaeapi_site')->list_domain($site_id);
        return $ret;
    }


    public function get_attr($site,$key)
    {
        if(in_array($key, $this->__runtime_attrs)){
            $ret = kernel::single('ecaeapi_site')->get_runtime_attr($site,$key);
        }else{
            $ret = kernel::single('ecaeapi_site')->get_attr($site,$key);
        }
        return $ret;
    }
    
    public function get_attrs($site)
    {
        $ret = kernel::single('ecaeapi_site')->dump_attr($site);
        foreach($ret AS $key=>$val){
            if(in_array($key, $this->__runtime_attrs)){
                $ret[$key] = kernel::single('ecaeapi_site')->get_runtime_attr($site,$key);
            }//fetch runtime attr
        }
        return $ret;
    }

    public function who_is($site,$uid)
    {
        $whoami = kernel::single('ecaeapi_site')->who_is($site,$uid);
        return $whoami;
    }

    public function set_users($site,$admin,$committer,$readonly,&$error=null,$uid=null)
    {
        $oUser = app::get('ecaeapi')->model('user');
 
        $arr_user = array();
        $has_create_user = false;
        $createuser = $this->get_createuser($site);
        foreach($admin as $k=>$v){
            if($v = trim($v)){
                if(!$oUser->is_exist($v)) {
                    $error = '用户'.$v.'不存在';
                    return false;
                }
                $admin[$k]=$v; 
                $arr_user[$v] = true; 
            }else{  
                unset($admin[$k]);
            }
            if($createuser['userid']==$v){$has_create_user = true;}
        }
        if(!$has_create_user) {
            $error = '用户'.$createuser['userid'].'（创建者）不能删除';
            return false;
        }
        if($uid && !$arr_user[$uid]){
            $error = '您不能将自己移出管理员列表';
            return false;
        }
        foreach($committer as $k=>$v){
            if($v = trim($v)){
                list($u,$p) = preg_split('/\s+/',$v);
                if(!$p){
                    $p = '/';
                }else{
                    $p = trim($p,'/');
                    $p = '/'.$p;
                }
                if(!$oUser->is_exist($u)) {
                    $error = '用户'.$u.'不存在';
                    return false;
                }
 
                if($u==$uid)continue;
                if($arr_user[$u])continue;
                $arr_user[$u] = true;
                $committer[$k] = array($u,$p);    
            }else{
                unset($committer[$k]);
            }
        }
        foreach($readonly as $k=>$v){
            if($v = trim($v)){
                list($u,$p) = preg_split('/\s+/',$v);
                if(!$p){
                    $p = '/';
                }elseif($p{0}!='/'){
                    $p = '/'.$p;
                }
                if($arr_user[$u]){
                    unset($readonly[$k]);continue;
                }
                if($u==$uid)continue;
                if(!$oUser->is_exist($u)) {
                    $error = '用户'.$u.'不存在';
                    return false;
                }
 
                $readonly[$k]=array($u,$p);
                $arr_user[$u] = true;
            }else{
                unset($readonly[$k]);
            }
        }
        
        if(!in_array($uid,$admin)){
            $admin[] = $uid;
        }

        $rst = kernel::single('ecaeapi_site')->add_users($site,
                array_values($admin),array_values($committer),array_values($readonly));
        return $rst;
    }
            
    public function list_user($site){
        $roles = kernel::single('ecaeapi_site')->list_user($site);
        return $roles;
    }

    #({shopex,aaa}, [{scm_info,0.1},{scm_info,0.2}])
    public function publish($site,$data,$default)
    {
        #if( !$this->check_path($path,$site) ) return false;
        ksort($data);
        foreach($data as $k => $v){
            $access = number_format($v['access']/100,2);
            $publish[] = array(ltrim($v['path'],'/'),$v['rev'],$access);
        }
        $re = kernel::single("ecaeapi_site")->publish($site,$publish,$default);
        return $re;
    }

    public function get_published( $site )
    {
        $list = kernel::single('ecaeapi_site')->get_published($site);
        if(is_array($list)){
            foreach((array)$list as $k => $row){
                $list[$k][1][3] = $row[1][3]*100;
            }
        }else{$list = array();}
        return array(
            $list,
            $default,
        );
    }

    public function get_publishing( $site )
    {
        $list = kernel::single('ecaeapi_site')->get_publishing($site);
	return $list ? $list : array();
    }



    public function get_default_published( $site )
    {
        return kernel::single('ecaeapi_site')->get_default_published($site);
    }
		
		public function nginx_get_rules($site)
		{
				return kernel::single('ecaeapi_site')->nginx_get_rules($site);
		}

		public function nginx_save_rules($site, $rules)
		{
				return kernel::single('ecaeapi_site')->nginx_save_rules($site, $rules);
		}

    public function env($site)
    {
        $env = kernel::single('ecaeapi_site')->env($site);
        foreach((array)$env as $k => $v){
            if($k{0}=='_') unset($env[$k]);
            if(strtoupper(substr($k,0,10))=='ECAE_MONGO') unset($env[$k]);
        }
        return $env;
    }

    public function addon_active($site,$type)
    {
        $ret =  kernel::single('ecaeapi_site')->addon_active($site,$type);
        return $ret;
    }


    public function log($site_id,$filter) 
    {
        if(!$site_id) return false;
        $info = $this->dump($site_id);

        $filter['site'] = $info['site']['id'];
        $offset = $filter['start'] ? $filter['start'] : 0;
        $limit = $filter['rows'] ? $filter['rows'] : 20;
        unset($filter['start']);
        unset($filter['rows']);
        $order = 'id';
        $ret = $this->app->model('log')->getlist($filter,$offset,$limit,$order);
        return $ret;
    }



    public function check_path($path,$site)
    {
        list($list,$default) 
            = self::get_published($site);
        foreach($list as $row) {
            if($path==$row[0][0]) return false;
        }
        return true;
    }

    
    function is_valid($name){
        return kernel::single('ecaeapi_site')->is_valid($name);
    }

    function is_exist($name){
        return kernel::single('ecaeapi_site')->is_exist($name);
    }

    function active($name){
        return kernel::single('ecaeapi_site')->active($name);
    }

    function deactive($name){
        return kernel::single('ecaeapi_site')->deactive($name);
    }

    public function check_site_name($name,&$msg){
        if(empty($name)){
            $msg = '请输入应用域名!';
            return false;
        }elseif(strlen($name)>14 || strlen($name)<=3) {
            $msg = '域名长度限制4-14个字符';
            return false;
        }elseif(preg_match("/[^a-z0-9-]/i",$name)){
            $msg = '域名只允许为字母、数字或-'; 
            return false;
        }elseif($name{0}=='-' || $name{strlen($name)-1}=='-'){
            $msg = '不允许以-开头或结尾'; 
            return false;
        }elseif($this->is_exist($name)){
            $msg = '该域名已存在';
            return false;
        }
        return true;
    }


    /*
     * 站点脚本文件列表
     */
    public function list_jobs($site_id) {
        try{
            $arr = ecae_agent_api()->job_script_site_list($this->site_id);
            return $arr;
        }catch(Exception $e){
            return false;
        }
    }


    public function get_createuser($siteid)
    {
        $createuser = $this->get_attr($siteid,'createuser');
        return array('agentid'=>$createuser[0],'userid'=>$createuser[1]);
    }


}
