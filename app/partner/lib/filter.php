<?php
class partner_filter {
	public function __construct($app) {
		$this->app = $app;
	}

	// 整理过滤条件 按key从小到大排序
	public function tidy($arrFilter) {
		ksort($arrFilter);
		$arrResult = array();
		foreach($arrFilter as $key => $row) {
			$arrResult[] = array($key,$row);
		}
		return $arrResult;
	} // end function tidy

    public function tidy_time($arrFilter,$key) {
        // 高级筛选过来
        if(isset($arrFilter['_'.$key.'_search']) ) {
            return  $this->_tidy_time($arrFilter,$key);
        } else {
            if(is_array($arrFilter[$key])) { 
                // array('bthan',123345)
                if(count($arrFilter[$key]) == 2 && isset($arrFilter[$key][0]) && isset($arrFilter[$key][1])) {
                    return array($arrFilter[$key][0],intval($arrFilter[$key][1]));
                }
                // array('between',123345,54321)
                if(count($arrFilter[$key]) == 3 && isset($arrFilter[$key][0]) && isset($arrFilter[$key][1]) && isset($arrFilter[$key][2])) {
                    return array('between',intval($arrFilter[$key][1]),intval($arrFilter[$key][2]));
                }
            } elseif(intval($arrFilter[$key])) {
                return intval($arrFilter[$key]);
            }
        }
        return false;
    } // end function tidy_time

    private function _tidy_time($arrFilter,$key) {
        if($arrFilter['_'.$key.'_search']=='between') {
            $from = strtotime($arrFilter[$key.'_from'].' '.$arrFilter['_DTIME_']['H'][$key.'_from'].':'.$arrFilter['_DTIME_']['M'][$key.'_from'].':00');
            $to   = strtotime($arrFilter[$key.'_to']  .' '.$arrFilter['_DTIME_']['H'][$key.'_to'].  ':'.$arrFilter['_DTIME_']['M'][$key.'_to'].':00');
            if(!empty($arrFilter[$key.'_from']) && empty($arrFilter[$key.'_to'])) {
                return array("bthan",intval($from)); 
            } elseif(empty($arrFilter[$key.'_from']) && !empty($arrFilter[$key.'_to'])){
                return array("lthan",intval($to));
            } elseif(!empty($arrFilter[$key.'_from'])&&!empty($arrFilter[$key.'_to'])){
                return array("between",intval($from),intval($to));
            }
        }
        $time = intval(strtotime($arrFilter[$key].' '.$arrFilter['_DTIME_']['H'][$key].':'.$arrFilter['_DTIME_']['M'][$key].':00'));
        if ($arrFilter['_'.$key.'_search'] == 'nequal')  return $time;
        return array($arrFilter['_'.$key.'_search'],$time);
    } // end function _tidy_time


}// end class 
