<?php
/**
 * 
 **/
class ecaeapi_mdl_job
{
    public function __construct( $app )
    {
        $this->app = $app;
    }

    function add($site_id,$task,$args){
        try{
            return ecaeapi_scripts::add_job($site_id,$task,$args);
        } catch(Exception $e) {
            return false;
        }
    }
    
    function jobs($site_id){
        try{
            return ecaeapi_scripts::jobs($site_id);
        } catch(Exception $e) {
            return false;
        }
    }
   
    function scripts($site_id){
        try{
            return ecaeapi_scripts::scripts($site_id);
        } catch(Exception $e) {
            return false;
        }
   }
    
    function script_info($site_id,$script){
        try{
            return ecaeapi_scripts::script_info($site_id,$script);
        } catch(Exception $e) {
            return false;
        }
    }
    
    function parse_args($attrs){
        try{
            return ecaeapi_scripts::parse_args($attrs);
        } catch(Exception $e) {
            return false;
        }
    }
    


}
