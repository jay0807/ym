<?php
/**
 * 
 **/
class ecaeapi_mdl_user
{
    public $msg;

    private $active_key = 'active_email';

    private $__alternation = array();




    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null)
    {
        $data = $this->lists();
        return $data;
    }


    public function count($filter=null)
    {
        $data = $this->getList( '*',$filter );
        return count($data);
    }

    function add($uid,$pwd,$email)
    {
        return kernel::single('ecaeapi_user')->add($uid,$pwd,$email);
    }

    public function change_password($uid,$oldpwd,$pwd)
    {
        return kernel::single('ecaeapi_user')->change_password($uid,$oldpwd,$pwd);
    }

    public function gen_password($uid){
        return kernel::single('ecaeapi_user')->gen_password($uid);
    }

    function save($data)
    {
        $uid = $data['name'];
        if( !$uid ) return false;
        unset($data['name']);
        if( !$data ) return false;
        foreach($data as $key => $val) {
            $re = $this->set_attr($uid,$key,$val);
            if( !$re ) return false;
        }
        return true;
    }

    function active($uid) {
        return $this->set_attr($uid,$this->active_key,'true');
    }


    function dump($uid)
    {
        $data = $this->get_attrs($uid);
        return $data;
    }

    public function check_password($uid, $pwd) 
    {
        return kernel::single('ecaeapi_user')->check_password($uid,$pwd);
    }//End Function

    public function set_attr($uid,$key,$val)
    {
        return kernel::single('ecaeapi_user')->set_attr($uid,$key,$val);
    }

    public function lists()
    {
        return kernel::single('ecaeapi_user')->lists();
    }


    public function get_attrs($uid)
    {
        $ret = kernel::single('ecaeapi_user')->dump_attr($uid);
        if( $ret && is_array($ret) ){
            foreach($ret as $r){
                $l[$r[0]] = $r[1];
            }
        } else {
            $l = array();
        }
        return $l;
    }

    function get_invite_num($uid){
        return $this->get_attr($uid,'invite_num');
    }

    function set_invite_num($uid,$add=false){
        $num = $this->get_invite_num($uid);
        $num = (int)$num;
        if($add)
            return $this->set_attr($uid,'invite_num',($num+1));
        else
            return $this->set_attr($uid,'invite_num',($num>0?($num-1):0));
    }

    public function get_attr($uid,$key)
    {
        $data = kernel::single('ecaeapi_user')->get_attr($uid,$key);
        return $data;
    }

    public function is_valid($uid){
        return kernel::single('ecaeapi_user')->is_valid($uid);
    }

    public function is_exist($uid){
        return kernel::single('ecaeapi_user')->is_exist($uid);
    }

    public function check_user_name($name,&$msg) {
        $len = strlen($name);
        if(!$name) {
            $msg = '用户名不能为空';
            return false;
        }elseif($len<6 || $len>20) {
            $msg = '用户名长度不符';
            return false;
        }elseif(preg_match('/[^a-z0-9_\.]/i',$name)) {
            $msg = '用户名只允许字母、数字、点及下划线组成';
            return false;
        }elseif(is_numeric($name{0})) {
            $msg = '用户名只允许位字母开头';
            return false;
        }elseif($name{0}=='_' || $name{0}=='.' || $name{$len-1}=='.'|| $name{$len-1}=='_') {
            $msg = '用户名不能以点和下划线做首尾';
            return false;
        }
        return true;
    }
    
    public function check_user_passwd($pwd,&$msg) {
        if(!$pwd) {
            $msg = '密码不能为空';
            return false;
        } elseif (strlen($pwd)<6 || strlen($pwd)>32) {
            $msg = '密码长度不符';
            return false;
        }
        return true;
    }

    public function check_user_email($email,&$msg) {
        if( !$email ) {
            $msg = 'email不能为空';
            return false;
        #} elseif( preg_match("/^\d+.*/is",$email) ) {
        #    $msg = 'email不能以数字开头！';
        #    return false;
        }  elseif(!preg_match("/^[a-z0-9]+\S*@\S*\.[a-z]+$/i",$email)) {
            $msg = 'email格式错误';
            return false;
        }
        return true;
    }

    function set_site_num_m($uid,$add=true){
        $count = $this->get_site_num_m($uid);

        $time = $this->get_attr($uid,'lasttime');
        if($time<=strtotime(date("Y-m-01"))) $count=0;

        $this->set_attr($uid,'monthcreatednum',$add?($count+1):(max($count-1,0)));
        $this->set_attr($uid,'lasttime',time());
    }

    function get_add_site_status($uid,&$msg) {
        #$re = $this->get_user_active_status($uid,$msg);
        #if(!$re) {
        #    $msg .= ',不能创建应用';
        #    return false;
        #}  

        $count = $this->get_site_num_m($uid);
        if($count>=$this->get_createsite_maxnum($uid)) {
            $msg = '每月创建应用个数不能超过'.$this->get_createsite_maxnum().'个';
            return false;
        }
        return true;
    }
    
    function get_createsite_maxnum($uid){
        if(!$this->__alternation){
            $alternation = kernel::single('ecaeapi_user')->get_alternation($uid);
            $this->__alternation = $alternation;
        }

        return $this->__alternation['max_num'];
    }

    function get_site_num_m($uid){
        if(!$this->__alternation){
            $alternation = kernel::single('ecaeapi_user')->get_alternation($uid);
            $this->__alternation = $alternation;
        }

        return $this->__alternation['create_num'];
    }

    public function get_user_active_status($uid,&$msg){
        $info = $this->dump($uid);
        if($info[$this->active_key]!=='true'){
            $msg = '您的邮箱未激活';
            return false;
        }
        return true;
    }


}
