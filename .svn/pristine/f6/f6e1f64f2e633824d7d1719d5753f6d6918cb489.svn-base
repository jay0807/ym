<?php
class partner_mdl_product extends partner_model{
	public function __construct($app) {
		parent::__construct($app);

		$this->_get();
	}

	private $_data;
	private function _get() {
		//$url = "http://platform.ec-ae.com/index.php/api";
		if(defined("PLATFORM_API_URL")) {
			$url = PLATFORM_API_URL;
		} else {
			$url = "http://test.ecae.local:8000/index.php/api";
		}
		$this->_data = kernel::single("partner_product")->getList($url);
	}
	
	public function count($filter=null){
		return count($this->_data);
	}

	public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
		$arrResult = array();
		foreach($this->_data as $row) {
			$arrResult[$row['product_id']] = array(
				'product_id'=>$row['product_id'],
				'name'=>$row['name'],
				'url'=>$row['url'],
				'create_date'=>$row['create_date'],
				'status'=>$row['status'],
				'site_id'=>$row['config']['site_id'],
				'agent'=>$row['config']['agent'],
				'config'=>$row['config'],
				'is_paas'=>$row['config']['is_paas'],
				'is_fork'=>$row['config']['is_fork']
			);
		}
		return $arrResult;
	}

	public function dump($id) {
		$arrData = $this->getList();
		return $arrData[$id];
	}

	
	public function get_schema(){
        $schema = array (
            'columns' => array (
			   'site_id' =>array (
			        'type' => 'varchar(10)',
			        'label' => app::get('appstore')->_('源站点'),
			        'width' => 120,
			        'required' => true,    
					'pkey' => true,
				),
				'agent' =>array (
			        'type' => 'varchar(10)',
			        'label' => app::get('appstore')->_('agent'),
			        'width' => 120,
			    ),
 		        'name' => array(
		            'type'=>'varchar(10)',
					'orderby'=>false,
					'label' => app::get('appstore')->_('产品名称'),
		        ),
			    'url'=>array(
			    	'type' => 'varchar(10)',
					'orderby'=>false,
					'label' => app::get('appstore')->_('产品URL'),
			    ),
			    'product_id'=>array(
					'type' => 'varchar(30)',
					'label' => app::get('appstore')->_('域名'),
					'width' => 200,
					'orderby'=>false,
			     ),
				'create_date'=>array(
					'type' => 'time',
					'orderby'=>false,
					'label' => app::get('appstore')->_('创建时间'),
			     ),
				'description'=>array(
					'type' => 'int(10)',
					'orderby'=>false,
					'label' => app::get('appstore')->_('描述'),
				),
				'status'=>array(
					'type'=>'bool',
					'orderby'=>false,
					'label' => app::get('appstore')->_('状态'),
				),
			),
		    'idColumn' =>'product_id',
			'in_list' => array (
					'name',
					'product_id',
					'site_id',
					'create_date',
					'status',
					'url',
					'agent',
			),
            'default_in_list' => array (
					'name',
					'create_date',
					'url',
					'product_id',
					'site_id',
					'status',
					'agent'
            ),
        );
        return $schema;
	}
}
	
