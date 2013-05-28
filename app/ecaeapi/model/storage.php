<?php
/**
 * 
 **/
class ecaeapi_mdl_storage
{
    

    function list_group($t='site',$site_id){
        try{
            return ecae_agent_api()->storage_list_group($t,$site_id);
        } catch(Exception $e) {
            return false;
        }
    }
    
    function register_group($site_id,$newgroup,$type='public',$public='false'){
        try{
            return ecae_agent_api()->storage_register_group($site_id,$newgroup,$type,$public);
        }catch(Exception $e){
            return false;
        }
    }


}
