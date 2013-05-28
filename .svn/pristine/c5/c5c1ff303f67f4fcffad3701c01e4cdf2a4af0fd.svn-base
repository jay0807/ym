<?php
/**
 * 
 **/
class ecaeapi_sphinx
{

    public function list_index($siteid)
    {
        try{
            return ecae_agent_api()->sphinx_list_index($siteid);
        }catch(Exception $e){
            return false;
        }
    }


    public function add_index($data)
    {
        try{
            return ecae_agent_api()->sphinx_add_index($data);
        }catch(Exception $e){
            echo "<pre>";print_r($e);exit;
            return false;
        }
    }

    public function get_index($siteid,$name)
    {
        try{
            return ecae_agent_api()->sphinx_get_index($siteid,$name);
        }catch(Exception $e){
            echo "<pre>";print_r($e);exit;
            return false;
        }
    }

    public function delete_index($siteid,$name)
    {
        try{
            return ecae_agent_api()->sphinx_delete_index($siteid,$name);
        }catch(Exception $e){
            echo "<pre>";print_r($e);exit;
            return false;
        }
    }



}
