<?php
class partner_job
{
	public function __construct($app) {
		$this->app = $app;
		$this->job = kernel::single("ecaeapi_scripts");
	}

	public function getLogList($site_id) {
		$arrResult =  $this->job->jobs($site_id);
		$arrResult = ($arrResult)? $arrResult : array();
		return array_reverse($arrResult);
	} // end function getLogList

	public function getJobList($site_id) {
		// 系统级的脚本
		$arrTemp =  $this->job->script_system_list();
		$arrResult['system'] = ($arrTemp)? $arrTemp: array();
		// 站点级的脚本
		$arrTemp =  $this->job->script_site_list($site_id);
		$arrResult['site'] = ($arrTemp)? $arrTemp: array();
		return $arrResult;
	} // end function getJobList(

	public function getSection($showlog=true) {
             $r = array(
                 'basic'=> array(
                             'label'=>app::get('partner')->_('任务信息'),
                             'file'=>'admin/site/job/basic.html',
                           ), // basic
               	'log'=> array(
                                'label'=>app::get('partner')->_('执行记录'),
                                'file'=>'admin/site/job/log.html',
                        	), // addons
             );
             if(!$showlog) unset($r['log']);
             return $r;
	}// end function getSection

	public function process($site_id,$arrData) {
		if($strMsg = $this->_vertify_job($arrData)) return array("error"=>$strMsg);
		// 还要整理参数的这里暂时不加

		$jobs = array();
		foreach($arrData['job'] as $row) {
			$jobs[] = trim($row);
		}
		try{
			$this->job->add_job($site_id,implode(" ",$jobs),$arrData['job_arg']);
		} catch(Exception $e) {
				return array("error"=>$e->getMessage());
			}
		return array("success"=>"任务添加成功");	
	} // end function process

	private function _vertify_job($arrData) {
		if(empty($arrData['job'])) return app::get("partner")->_("请选择要添加的任务!"); 
		return false;
	} // end function _vertify_job 

} // end class
