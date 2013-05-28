<?php
class ecaeapi_product {
	private $instance = null;
	
	public function __construct($app) {
		$this->app = $app;
		$this->instance = ecaeapi_api::getInstance();
	}

	// 添加一个商品
	// 商品名称,应用,价格(给下级代理商的),开通资源 
    function add($product_name,$app_id,$price = 0,$parts = array()) {
        try{
            return $this->instance->goods_products_add($product_name,$app_id,intval($price),$parts);
        }catch(Exception $e){
            return false;
        }
    }// end function add 

	// 激活商品
	public function active($product_id) {
		try{
            return $this->instance->goods_products_up($product_id);
        } catch (Exception $e) {
            return false;
        }
	} // end function active

	// 冻结商品	
	public function deactive($product_id) {
		try{
            return $this->instance->goods_products_down($product_id);
        } catch (Exception $e) {
            return false;
        }
	} // end function deactive

	// 设置商品状态
	public function set_status($product_id,$status = "up") {
		if("up" == $status) {
			return $this->active($product_id);
		} else {
			return $this->deactive($product_id);
		}
	} // end function set status


	// 设置商品价格	
	public function set_price($product_id,$price) {
		try{
            return $this->instance->goods_products_set_price($product_id,intval($price));
        } catch (Exception $e) {
            return false;
        }
	} // end function set_price

	// 设置商品简介(主要用于下级代理商查看)	
	public function set_intro($product_id,$intro = "") {
		try{
            return $this->instance->goods_products_set_introduction($product_id,$intro);
        } catch (Exception $e) {
            return false;
        }
	} // end function set_intro

	// 获取商品详情
	public function get_info($product_id) {
		try{
            return $this->instance->goods_products_get_info($product_id);
        } catch (Exception $e) {
            return false;
        }
	} // end function get_info

	// 设置商品详情
	public function set_info($product_id,$arrData) {
        return $this->instance->goods_products_set_info($product_id,$arrData);
	} // end function set_info

	
	// 获取商品所有扩展信息
	public function dump_attr($product_id) {
		try{
            return $this->instance->goods_products_dump_attr($product_id);
        } catch (Exception $e) {
            return false;
        }
	} // end function dump_attr

	// 获取商品指定的扩展信息
	public function get_attr($product_id,$key) {
		try{
            return $this->instance->goods_products_get_attr($product_id,$key);
        } catch (Exception $e) {
            return false;
        }
	} // end function get_attr
	
	// 设置商品指定的扩展信息
	public function set_attr($product_id,$key,$value) {
		try{
            return $this->instance->goods_products_set_attr($product_id,$key,$value);
        } catch (Exception $e) {
            return false;
        }
	} // end function set_attr

	// 获取所有的商品
	public function get_list() {
		try{
            return $this->instance->goods_products_list();
        } catch (Exception $e) {
            return false;
        }
	} // end function get_list

} // end class
