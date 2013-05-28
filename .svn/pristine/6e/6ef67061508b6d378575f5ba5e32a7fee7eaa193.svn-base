<?php
/**
 * 
 **/
class partner_ctl_admin_product extends partner_controller {
	
	public function index() {
		$this->finder('partner_mdl_product',array(
			'title'=>app::get("partner")->_('产品列表'),
			'actions'=>array(
				array(
					'label'=>app::get('partner')->_('同步产品信息'),
					'submit'=>'index.php?app=partner&ctl=admin_site&act=status&p[0]=1',
					'confirm'=> app::get('partner')->_('确定?'),
				),
				/*
				array(
					'label'=>app::get('partner')->_('添加产品'),
					'href'=>'index.php?app=partner&ctl=admin_product&act=add',
					'target'=>'dialog::{title:\''.app::get('partner')->_('添加产品').'\',width:600,height:360}'
				),*/
		    ),
		    //'use_buildin_filter'=>true,
		    'use_buildin_recycle'=>false,
		    //'use_buildin_export'=>true,
		));
	}

	public function add() {
		$this->display("admin/product/edit.html");
	}

	public function edit($site_id) {
		$objSite = $this->app->model('site');
		$arrData = $objSite->dump(intval($site_id));
		
		$this->pagedata['id'] = $site_id;
		$this->pagedata['data'] = $arrData;
		$this->display("admin/site/edit.html");
	}

	public function save() {
		$this->begin("index.php?app=partner&ctl=site&act=index&finder_id=".$_GET['finder_id']);
		$objSite = kernel::single("partner_site");
		$this->end($objSite->save($_POST['data'],$strMsg),$strMsg);
	}

	// 启用&停用
	public function status($status) {
		$this->begin("index.php?app=partner&ctl=site&act=index&finder_id=".$_GET['finder_id']);
		$objSite = kernel::single("partner_site");
		$this->end($objSite->setStatus($_POST,$status,$strMsg),$strMsg);
	}

	public function log($site_id) {
		$objSite = kernel::single("partner_api_site");
		$arrSite = $objSite->getNameById($site_id);

		if(empty($arrSite)) die("无效站点");

		list($agent,$site) = $arrSite[0]['name'];
		
		// 发布版本 [0][1] 是默认要查询的日志,ecae管理日志没有这个
		$arrPublish = $objSite->getDeployLabel($agent,$site);
		$publish = empty($arrPublish)? "" : $arrPublish[0][1];

		$this->finder('partner_mdl_log',array(
			'title'=>'站点('.$site_id.')日志列表(默认为ecae管理日志),无发布点的话默认为('.$publish.')',
			'actions'=>array(
				array(
					'label'=>app::get('partner')->_('查看发布点'),
					'href'=>'index.php?app=partner&ctl=site&act=publish_info&p[0]='.$site_id,
					'target'=>'dialog::{title:\''.app::get('partner')->_('查看发布点').'\',width:600,height:360}'
				),
			),
			'base_filter'=>array('site_id'=>$site_id),
		    'use_buildin_filter'=>true,
		    'use_buildin_recycle'=>false,
		));
	}

	// 查看发布点
	public function publish_info($site_id) {
		$objSite = kernel::single("partner_api_site");
		$arrSite = $objSite->getNameById($site_id);

		list($agent,$site) = $arrSite[0]['name'];
		
		// 发布版本 [0][1] 是默认要查询的日志,ecae管理日志没有这个
		$arrPublish = $objSite->getDeployLabel($agent,$site);
		$this->pagedata['data'] = $arrPublish;
		$this->display("site/publish_info.html");
	}

}
