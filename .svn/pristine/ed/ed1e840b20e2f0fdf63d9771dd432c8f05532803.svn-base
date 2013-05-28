<?php
class partner_ctl_admin_streamlog extends partner_controller {
	
	public function index($site) {
		$this->pagedata["sections"] = $this->getSection();
		$this->pagedata['site'] = $site;
		$this->pagedata['time_now'] = strtotime("-30 minute");
		$this->singlepage("admin/site/streamlog/index.html");
	} // end function index

	public function search(){
		$site = $_GET['site'];
		if(!$site) exit("site is empty!");
		$arr_site_info = ecae_agent_api()->site_get_info($site);
		$filter = $this->get_filter();

		$filter = array_merge($filter, array(
				'site'=>$arr_site_info['id'],
				'type'=>$_POST['search_name'],
                		'status' => $_POST['status']==1 ? 1 : array('$ne'=>1),
			));
		
		if(!$_POST['search_type'])exit("search error!");
		if(!in_array($_POST['search_type'],array('queue','crontab','job'))) exit("search type error! not exists");
		$func = "{$_POST['search_type']}_log";
		$log= ecae_agent_api()->$func($filter);
		$this->pagedata['log'] = $log['data'];
		$this->pagedata['log_count'] = $log['count'];
		$this->pagedata['search_time'] = $log['search_time'];
		$this->display("admin/site/streamlog/log.html");
	}

	
	private function get_filter(){
		$filter = array();
		$filter['time'] = strtotime($_POST['time']." ".$_POST['_DTIME_']['H']['time'] . ":" . $_POST['_DTIME_']['M']['time']);
		//$filter['end_time'] = strtotime($_POST['end_time']." ".$_POST['_DTIME_']['H']['end_time'] . ":" . $_POST['_DTIME_']['M']['end_time']);
		$filter['limit'] = (int)$_POST['limit'];
		$filter['offset'] = (int)$_POST['offset'];
		return $filter;
	}

	private function getSection() {
             $r = array(
                 'job'=> array(
                             'label'=>'job',
                           ), // basic
                 'crontab'=> array(
                             'label'=>'crontab',
                           ), // basic
                 'queue'=> array(
                             'label'=>'queue',
                           ), // basic
             );
             return $r;
	}// end function getSection


}
