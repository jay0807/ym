<?php

class site_ctl_proinstance extends site_controller 
{
    
    public function index() 
    {
        $this->pagedata['file'] = 'site_proinstance:'.$this->_request->get_param(0);
        $this->page('proinstance.html', true);
    }//End Function
    

}//End Class