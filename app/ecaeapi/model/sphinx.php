<?php
/**
 * 
 **/
class ecaeapi_mdl_sphinx
{

    public function list_index($siteid)
    {
        return $this->sphinx()->list_index($siteid);
    }

    public function add_index($siteid,$data)
    {
        if(!$data['name']) return false;
        $options = array();
        foreach((array)$data['options'] as $col => $row){
            $options[$col] = array(
                $row['attr_type'],
                $row['alias'],
                );
        }
        $arr = array(
                $siteid,$data['name'],$data['table'],$data['type'],$data['charset'],$data['update_frequency'],$options
            );
        return $this->sphinx()->add_index($arr);
    }

    public function get_index($siteid,$name)
    {
        return $this->sphinx()->get_index($siteid,$name);
    }

    public function delete_index($siteid,$name)
    {
        return $this->sphinx()->delete_index($siteid,$name);
    }



    private function sphinx()
    {
        return kernel::single('ecaeapi_sphinx');
    }



}
