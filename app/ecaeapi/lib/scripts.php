<?php
class ecaeapi_scripts{
    
    static function add_job($site_id,$task,$args){
#        list($type,$name) = explode(':',$task);
        
#        $a = array();
#        foreach($args as $p){
#            $a[]='"'.str_replace('"','\\"',$p).'"';
#        }
#        $args = implode(',',$a);
        
        ecae_agent_api()->job_add($site_id,"site",$task,$args);
    }
    
    static function jobs($site_id){
        return ecae_agent_api()->job_list($site_id);
    }
    
    static function scripts($site_id){
        $scripts['system'] = ecaeapi_scripts::script_system_list();

        $scripts['site'] = ecaeapi_scripts::script_site_list($site_id);

        foreach($scripts as $k=>$v){
            foreach($v as $s){
                $fixed_scripts[$k][$s['file']] = $s;
            }    
        }
        return $fixed_scripts;
    }


    static function script_site_list($site_id){
        return ecae_agent_api()->job_script_site_list($site_id);
    }


    static function script_system_list(){
        return ecae_agent_api()->job_script_system_list();
    }
    





####################################################################################
/*
    static function script_info($site_id,$script){
        list($where,$file) = explode(':',$script);
        if($where=='system'){
            $info = ecae_agent_api() ->job_script_system_info($file);
        }elseif($where=='site'){
            $info = ecae_agent_api() ->job_script_site_info($site_id,$file);
        }else{
            $info = array('error','undefined');
        }
        $attrs = array();
        foreach($info as $r){
            $attrs[$r[0]] = $r[1];
        }
        return $attrs;
    }
    
    static function parse_args($attrs){
        if($attrs['args']){
            foreach(explode(',',$attrs['args']) as $r){
                $p = strpos($r,'(');
                if($p){
                    $k = substr($r,0,$p);
                    list($type,$opt) = explode(':',substr($r,$p+1,strrpos($r,')')-$p-1));
                    $args[] = self::fixed_attr($k,$type,$opt);
                }else{
                    $args[] = array('label'=>$r);
                }
            }
        }
        return $args;
    }
    
    static private function fixed_attr($k,$type,$opt){
        return array(
                'label'=>$k,
            );
    }
    
    static private function fixed_attr_storage($opt){
        
    }

*/



}
