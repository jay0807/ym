<?php
/**
 * 
 **/
class ecaeapi_mdl_memcache
{

    public function host( $site )
    {
        $ret = $this->lists($site);
        if($ret){
            try{
                return ecae_agent_api()->memcache_ip($site);
            } catch(Exception $e) {
                return false;
            }
        }else{
            return null;
        }

    }

    public function lists($site)
    {
        try{
            return ecae_agent_api()->memcache_list($site);
        } catch(Exception $e) {
            return false;
        }
    }

}
