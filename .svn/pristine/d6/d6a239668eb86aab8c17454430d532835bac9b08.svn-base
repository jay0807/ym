<?php

class ecaeapi_queue{
    

    public function add_task($site_id,$url,$type="curl")
    {
        if( !$url ) return false;
        return ecae_agent_api()->queue_add_task($siteid,$url,$type);
    }


    public function log($type="php_loop",$filter=array())
    {
        if( !$filter ) {
            return kernel::single('ecaeapi_streamlog')->conn($type)->find();
        } else{
            $time = time();
            if( $filter['time'] ) $time = $filter['time'];
            unset($filter['time']);
            return kernel::single('ecaeapi_streamlog')->conn($type,$time)->find($filter);
        }
    }

}

