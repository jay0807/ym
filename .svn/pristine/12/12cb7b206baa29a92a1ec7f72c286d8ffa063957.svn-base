<?php
class ecaeapi_crontab{
    

    function create($site,$id,$label,$entry){
        return ecae_agent_api()->crontab_create($site,$id,$label,$entry);
    }

    /*
     * @params      $ids        array
     */
    public function delete($site,$ids)
    {
        return ecae_agent_api()->crontab_delete($site,(array)$ids);
    }

    public function lists($site)
    {
        return ecae_agent_api()->crontab_list($site);
    }
    
    public function expand($site,$entry)
    {
        return ecae_agent_api()->crontab_expand($site,$entry);
    }


    public function log($filter)
    {
        // code...
    }

}

