<?php
class partner_addon_storage
{

	public function __construct($app) {	
		$this->app = $app;
	}


	public function add($site,$dir_name) {
		$dir_name = str_replace(array("\r\n","\r","\n"),"\r\n",$dir_name); 
		$arrDir = explode("\r\n",$dir_name);
		$objStorage = app::get("ecaeapi")->model("storage"); 
		foreach($arrDir as $row) {
			list($dir,$access) = explode("|",$row);
			$access = ($access == 'private')? "false" : "true";
			if($dir) $objStorage->register_group($site,trim($dir),'public',$access);
		}
	}
}
