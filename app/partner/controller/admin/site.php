<?php
class partner_ctl_admin_site extends partner_controller {
    
    public function index() {
        $actions = array();
        if($this->checkPermission("ecae_site_add",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('添加站点'),
                    'href'=>'index.php?app=partner&ctl=admin_site&act=add',
                    'target'=>'dialog::{title:\''.app::get('partner')->_('添加站点').'\',width:700,height:500}'
            );
        }
        if($this->checkPermission("ecae_site_batch_status",true)) {
            $actions[] = array(
                    'label'=>app::get('partner')->_('开启站点'),
                    'submit'=>'index.php?app=partner&ctl=admin_site&act=status&p[0]=1',
                    'confirm'=> app::get('partner')->_('确定开启选中的站点?'),
            );
            $actions[] = array(
                    'label'=>app::get('partner')->_('关闭站点'),
                    'submit'=>'index.php?app=partner&ctl=admin_site&act=status&p[0]=0',
                    'confirm'=>app::get('partner')->_('确定关闭选中的站点?'),
            );
        }                
        if($this->checkPermission("ecae_site_task",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('站点任务'),
                    'submit'=>'index.php?app=partner&ctl=admin_site&act=task',
                    'target'=>'dialog::{title:\''.app::get('partner')->_('添加任务').'\',width:900,height:360}'
            );
        }
        if($this->checkPermission("ecae_site_varnish",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('站点Varnish更新'),
                    'submit'=>'index.php?app=partner&ctl=admin_site&act=varnish',
                    'confirm'=>app::get('partner')->_('确定更新选中的站点varnish?'),
            );
        }
        if($this->checkPermission("ecae_site_nginx_batch",true)) {
            $actions[]=    array(
                    'label'=>app::get('partner')->_('站点web设置批量处理'),
                    'submit'=>'index.php?app=partner&ctl=admin_site&act=nginx_batch',
                    'target'=>'_blank',
            );
        } 
        if($this->checkPermission("ecae_site_delete",true)) {
            $actions[]=    array(
                    'label'   => app::get('partner')->_('删除'),
                    'submit'  => 'index.php?app=partner&ctl=admin_site&act=delete',
                    'confirm' => app::get('partner')->_('确定删除选中的站点?'),
                    //'target'=>'dialog::{title:\''.app::get('partner')->_('删除站点').'\',width:600,height:360}'
            );
        }

        $this->finder('partner_mdl_site',array(
            'title'               => app::get("partner")->_('站点列表'),
            'actions'             => $actions,
            'use_buildin_filter'  => true,
            'use_buildin_recycle' => false,
            //'use_buildin_export'=>true,
        ));
    } // end function index

    // 站点添加
    public function add() {
        $this->checkPermission("ecae_site_add");
        $this->_site_common();
        $this->display("admin/site/create.html");
    } // end function add 

    private function _site_common() {
        $arrDomain = kernel::single("ecaeapi_agent")->getDomainList();
        $this->pagedata['domain'] = $arrDomain[0]['domain'];
    } // end function _site_common

    // 编辑站点
    public function edit($site_id) {
        $this->checkPermission("ecae_site_edit");

        if($_POST) {
            $this->begin("index.php?app=partner&ctl=admin_site&act=index&finder_id=".$_GET['finder_id']);
            $this->end(kernel::single("partner_site")->update($_POST['data'],$strMsg),$strMsg);
        } else {
            $arrData = $this->app->model('site')->dump($site_id);
            $this->pagedata['id']    = $site_id;
            $this->pagedata['data']  = $arrData; 
            $this->display("admin/site/edit.html");
        }
    } // end function edit

    // 站点保存
    public function save() {
        $this->begin("index.php?app=partner&ctl=admin_site&act=index&finder_id=".$_GET['finder_id']);
        $arrData = $_POST['data'];
        if(isset($_POST['_DTIME_'])) {
            $arrData['starttime'] = ($_POST["create_time_type"])? time() : strtotime($_POST['starttime']." ".$_POST['_DTIME_']['H']['starttime'].":".$_POST['_DTIME_']['M']['starttime'].":00");
        }
        $this->end(kernel::single("partner_site")->save($arrData,$strMsg),$strMsg);
    }// end function save

    // 启用&停用
    public function status($status) {
        $this->checkPermission("ecae_site_batch_status");
        $this->begin("index.php?app=partner&ctl=admin_site&act=index&finder_id=".$_GET['finder_id']);
        $objSite = kernel::single("partner_site");
        $this->end($objSite->setStatus($_POST,$status,$strMsg),$strMsg);
    } // end function status
    
    // 站点域名管理
    public function domain($site_id) {
        $this->checkPermission("ecae_site_domain");
        if(isset($_POST) && $_POST) {
            echo json_encode(kernel::single("partner_domain")->setDomain($site_id,$_POST)); exit;
        }

        $this->pagedata['site_id'] = $site_id;
        $this->pagedata['data'] = kernel::single("partner_domain")->getDomainList($site_id);
        $this->singlepage("admin/site/domain.html");
    } // end function domain 
        
    // 站点用户管理(paas站点)
    public function user($site_id) {
        $this->checkPermission("ecae_site_user");
        if(isset($_POST['data']) && $_POST['data']) {
            echo json_encode(kernel::single("partner_site")->setUser($site_id,$_POST['data']));
            exit;
        }
        $this->pagedata['data'] = kernel::single("partner_site")->getUser($site_id,true);
        $this->pagedata['site_id'] = $site_id;
        $this->singlepage("admin/site/user.html");
    }// end function user

    // ip过滤
    public function ip($site_id) {
        $this->checkPermission("ecae_site_ip");
        if(isset($_POST['data']) && $_POST['data']) {
            echo json_encode(kernel::single("partner_site")->setIpFilter($site_id,$_POST['data']));
            exit;
        }
        $this->pagedata['data'] = kernel::single("partner_site")->getIpFilter($site_id);
        $this->pagedata['site_id'] = $site_id;
        $this->display("admin/site/ip.html");
    }// end function ip

    // 站点发布管理
    public function publish($site_id) {
        $this->checkPermission("ecae_site_publish");
        $objSite = kernel::single("partner_site");
        $arrPublish = $objSite->getPublishList($site_id);
        // 是否是静态站点
        $static = (!is_array($arrPublish[0][0]) && "static" == $arrPublish[0][0])? true : false;
        if(isset($_POST) && $_POST) {
            echo json_encode(kernel::single("partner_site")->publish($site_id,$_POST)); exit;
        } else {
            $arrPublishing = $objSite->getPublishingList($site_id); // 正在发布的版本 
            if(!$static) {
                $arrDomain = kernel::single("partner_domain")->getDomainList($site_id);
                $this->pagedata['site_url']        = $arrDomain[0]["url"];
                $this->pagedata['site_id']         = $site_id;
                $this->pagedata['data']            = $arrPublish;
                $this->pagedata['publishing']      = $arrPublishing;
                $this->pagedata['default_publish'] = $objSite->getDefaultPublished($site_id);
            }
            $this->pagedata['static'] = $static;
            if($arrPublishing) {
                $this->singlepage("admin/site/publishing.html");
            } else {
                $this->singlepage("admin/site/publish.html");
            }
        }
    } // end function publish



    // 任务批量处理
    public function task() {
        $this->checkPermission("ecae_site_task");

        $arrResult = array();
        switch(true) {
            case isset($_POST['site_id']) :
                $arrResult = $_POST['site_id'];
                break;
            case isset($_POST["isSelectedAll"]):
                $arrResult = kernel::single("partner_site")->getSiteID($_POST);
                break; 
        }
        if(!empty($arrResult)){
            $scm = array();
            $objSite = kernel::single("ecaeapi_site");
            foreach($arrResult as $site_id){
                $current_scm = $objSite->get_attr($site_id, "scm");
                if(!empty($scm) && $scm!=$current_scm) {
                    $scm = array();
                    break;
                }
                $scm = $current_scm; unset($current_scm);
            }
            if($scm) {
                $objJob = kernel::single('partner_job');
                $this->pagedata['job'] = $objJob->getJobList($site_id);
                $this->pagedata['sections'] = $objJob->getSection(0);
                $this->pagedata['site_id'] = $_POST['site_id'];
                $this->display("admin/site/job/frame.html");
            }else{
                echo app::get("partner")->_("所选站点不符合规则(您选择了多个源站点),批量添加任务只适用于同一个svn源的站点.");
            }
        }else{
            echo app::get("partner")->_("参数错误!请选择站点");
        }
    } // end function task

    public function job($site_id) {
        $this->checkPermission("ecae_site_task");
                $objJob = kernel::single("partner_job");    
                if(isset($_POST['data']) && $_POST['data'] && $_POST['site_id']) {
                    $return = array();
                    foreach($_POST['site_id'] as $site_id) {
                        $r = $objJob->process($site_id,$_POST['data']);
                        if(isset($r['error'])){
                            $return['site_id'][] = $site_id;
                        }
                    }
                    if(empty($return)) {
                        $return = $r;
                    } else {
                        $return['error'] = '站点ID：'.implode(',',$return['site_id']).',添加任务失败';
                        unset($return['site_id']);
                    }
                    echo json_encode($return);exit;
        } else { 
            #$this->pagedata["log"] = $objJob->getLogList($site_id);
            $this->pagedata["job"] = $objJob->getJobList($site_id);
            $this->pagedata["site_id"] = array($site_id);
            $this->pagedata["sections"] = $objJob->getSection();
                }
        $this->singlepage("admin/site/job/frame.html");
    }


    // 数据库管理
    public function mysql($site_id) {
        $this->checkPermission("ecae_site_mysql");
        $arrEnv = kernel::single("ecaeapi_site")->env($site_id);
        kernel::single('base_session')->start();
        $_SESSION['ecae_env'] = $arrEnv;
        $this->pagedata['url'] = kernel::base_url(true)."/tools/phpmyadmin/index.php?".time();
        $this->display("admin/site/mysql.html");
    } // end function mysql
    
    // php设置
    public function phpini($site_id) {
        $this->checkPermission("ecae_site_phpini");
        $objSite = kernel::single("partner_site");
        if(!empty($_POST)) {
            echo json_encode($objSite->setPhpIni($site_id,$_POST['phpini']));exit;
        } else {    
            $this->pagedata['php_ini'] = $objSite->getPhpIni($site_id); 
            $this->pagedata["site_id"] = $site_id;
            $this->singlepage("admin/site/phpini.html");
        }
    } // end function nginx


    public function nginx_batch() {
        $arrResult = array();
        switch(true) {
            case isset($_POST['site_id']) :
                $arrResult = $_POST['site_id'];
                break;
            case isset($_POST["isSelectedAll"]):
                $arrResult = kernel::single("partner_site")->getSiteID($_POST);
                break; 
        }
        if(!empty($arrResult)){
            $this->pagedata['site'] = $arrResult;
            $objSite = kernel::single("partner_site");
            $arrConstant = $objSite->getNginxConstant();
            $this->pagedata['config'] = array('limit'=>5,'rule'=>array());
            $this->pagedata['options'] = $arrConstant['options'];
            $this->pagedata['expires_option'] = $arrConstant['expires_option'];

            $this->pagedata['nginx_setting_batch'] = true;
            $this->pagedata['form_submit_url'] = app::get('desktop')->router()->gen_url(
                    array('app'=>'partner', 'ctl'=>'admin_site', 'act'=>'nginx_batch_save'), true
                );

            $this->singlepage("admin/site/nginx.html");
        }else{
            echo app::get("partner")->_("site is empty!");
        }
    } // end function nginx_batch

    public function nginx_batch_save() {
        $this->checkPermission("ecae_site_nginx");
        if(empty($_POST))  exit(json_encode(array("error"=>app::get("partner")->_("设置失败")))); 
        if(empty($_POST['site']))  exit(json_encode(array("error"=>app::get("partner")->_("error! site is empty")))); 
        $objSite = kernel::single("partner_site");
        foreach($_POST['site'] as $site_id){
            $r = $objSite->setNginxConfig($site_id,$_POST);
            if(!$r['success'])
                exit(json_encode(array("error"=>app::get("partner")->_("failed,site: <{$site_id}>")))); 
        }
        echo json_encode($r);exit;
    } // end function nginx_batch_save


    // nginx设置
    public function nginx($site_id) {
        $this->checkPermission("ecae_site_nginx");
        $objSite = kernel::single("partner_site");
        if(!empty($_POST)) {
            echo json_encode($objSite->setNginxConfig($site_id,$_POST));exit;
        } else {
            $this->pagedata['form_submit_url'] = app::get('desktop')->router()->gen_url(
                    array('app'=>'partner', 'ctl'=>'admin_site', 'act'=>'nginx', 'p[0]'=>$site_id), true
                );
            $arrConstant = $objSite->getNginxConstant();
            $this->pagedata['options'] = $arrConstant['options'];
            $this->pagedata['expires_option'] = $arrConstant['expires_option'];
            $this->pagedata['config'] = $objSite->getNginxConfig($site_id); 
            $this->pagedata["site_id"] = $site_id;
            $this->singlepage("admin/site/nginx.html");
        }
    } // end function nginx

    // 站点删除    
    public function delete() {
        $this->checkPermission("ecae_site_delete");
        $this->begin("index.php?app=partner&ctl=admin_site&act=index&finder_id=".$_GET['finder_id']);
        $this->end(kernel::single("partner_site")->delete($_POST,$strMsg),$strMsg);
    } // end function delete

    // 站点开通周期设置
    public function deadline($site_id) {
        $this->checkPermission("ecae_site_deadline");
        if(!empty($_POST['data'])) {
            echo json_encode(kernel::single("partner_site")->setDeadLine($site_id,$_POST['data']));exit;
        } else {
            $this->pagedata['data']    = $this->app->model('site')->dump($site_id);
            $this->pagedata["now"]     = time();
            $this->pagedata["site_id"] = $site_id;
            $this->display("admin/site/deadline.html");
        }
    } // end function deadline

    // 批量更新站点varnish
    public function varnish() {
        $this->checkPermission("ecae_site_varnish");
        $this->begin("index.php?app=partner&ctl=admin_site&act=index&finder_id=".$_GET['finder_id']);
        $this->end(kernel::single("partner_site")->clearVarnish($_POST,$strMsg),$strMsg);
    } // end function varnish

}// end class
