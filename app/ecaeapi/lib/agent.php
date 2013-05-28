<?php
class ecaeapi_agent {
    public function getDomainList()
    {
        try{
            return ecae_agent_api()->agent_list_domain();
        } catch (Exception $e) {
            return false;
        }
	}

} // end class
