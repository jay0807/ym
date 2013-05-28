<?php
class partner_product
{
	public function __construct($app) {
		$this->app = $app;
	}

	public function save($arrData,&$strMsg) {
		// 验证
		if($strMsg = $this->verify($arrData)) return false;

		$objProduct = kernel::single("ecaeapi_product");
		if(empty($arrData['product_id'])) {
			if(!$product_id = $this->_add($arrData,$strMsg)) {
				return false;
			}
		} else {// edit product
			$product_id = $arrData['product_id'];
			$arrProduct = array(
				array("price",intval($arrData['price'])),
				array("status",$arrData['status']),
				array("name",trim($arrData['name'])),
				array("parts",$this->_part($arrData)),
			);
			$objProduct->set_info($product_id,$arrProduct);

		}

		// product price
		//if(isset($arrData['product_id']) && !empty($arrData["product_id"])) {
		//	$objProduct->set_price($product_id,intval($arrData['price']));
		//}
		// product intro
		if(isset($arrData['product_id'])) {
			$objProduct->set_intro($product_id,$arrData['intro']);
		}
		// product status
		if(empty($arrData['product_id']) && $arrData['status'] == 'down') {
			$objProduct->set_status($product_id,$arrData['status']);
		}

		$strMsg = app::get("partner")->_("添加成功");
		return true;
	} // end function save

	private function _add($arrData,&$strMsg) {
		$objProduct = kernel::single("ecaeapi_product");
		$name = trim($arrData['name']);

		$arrPart = $this->_part($arrData);
		return $objProduct->add(
			$arrData['name'],
			$arrData['app_id'],
			intval($arrData['price']),
			$arrPart
		);
	} // end function add

	private function _part($arrData = array()) {
		$arrResult = array();
		if(!isset($arrData['part'])) return $arrResult;
		foreach($arrData['part'] as $key=>$row) {
			$num = intval($row['num']);
			if(!$num) continue;
			$arrResult[] = $key;
			$arrResult[] = $num;
		}
		return $arrResult;
	}

	// 验证
	private function verify($arrData) {
		if(!isset($arrData['name']) || empty($arrData['name'])) return app::get("partner")->_("产品不能为空");
		if(!isset($arrData['app_id']) || empty($arrData['app_id'])) return app::get("partner")->_("请选择应用");
		if(!isset($arrData['price']) || empty($arrData['price'])) return app::get("partner")->_("价格不能为空");
		return false;
	}// end function verify

	// 设置状态(上架&&下架)
	public function setStatus($arrData,$status,&$strMsg) {
		if(!isset($arrData['product_id']) || count($arrData['product_id']) > 20) {
			$strMsg = app::get("partner")->_("一次处理最多20条数据!");
			return false;
		}
		
		$objProduct = kernel::single("ecaeapi_product");
		foreach($arrData['product_id'] as $row) {
			$objProduct->set_status($row,$status);
		}

		$strMsg = app::get("partner")->_("操作成功");
		return true;
	} // end function setStatus

} // end class
