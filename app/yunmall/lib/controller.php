<?php
class yunmall_controller extends site_controller{
    
    public function page404($message = "访问出错" ) {
        $this->begin($this->gen_url(array('app'=>'site', 'ctl'=>'default')));
        $this->end(false, $message);
    } // end function page404

    public function explode_param($param) {
        if(!$param) return null;
        $arrParam = explode(",",$param);
        return intval($arrParam[0]);
    } // end function explode($param)

} // end class
