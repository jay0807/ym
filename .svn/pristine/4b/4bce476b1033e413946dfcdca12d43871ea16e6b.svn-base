<?php
class ecaeapi_site{
    private $instance = null;
    
    public function __construct($app) {
        $this->app = $app;
        $this->instance = ecaeapi_api::getInstance();
    }

    public function get_all_info($sid)
    {
        try{
            $arr = $this->instance->site_get_all_info($sid);
            $arr['default_domain_url'] = 'http://'.$arr['default_domain'].(ECAE_DOMAIN_PORT==80?'':':'.ECAE_DOMAIN_PORT);
            return $arr;
        } catch (Exception $e) {
            return false;
        }
    }

    function delete($site_id,$secret = null,$v = 1){
        if($v == 1) {
            return $this->instance->site_delete($site_id,$secret);
        } else {
            return $this->instance->site_delete($site_id);
        }
    } // 删除站点

    // 开通站点
    function create($uid,$name,$product_id = null) {
        try{
            if($product_id)
                return $this->instance->site_create($uid,$name,$product_id);
            else
                return $this->instance->site_create($uid,$name);
        } catch(Exception $e) {
            return false;
        }
    } // end function create

    public function deploy_label($sid)
    {
        try{
            return $this->instance->site_deploy_label($sid);
        } catch(Exception $e) {
            return false;
        }
    }


    public function set_attr($name,$key,$val)
    {
        try{
            return $this->instance->site_set_attr($name,$key,$val);
        } catch(Exception $e) {
            return false;
        }
    }

    public function list_site_alias($uid)
    {
        try{
            return $this->instance->user_list_site_alias($uid);
        } catch(Exception $e) {
            return false;
        }
    }

    // 获得默认域名
    public function default_domain($site){
        try{
            $ret = $this->instance->site_default_domain($site);
            return $ret;
        } catch( Exception $e){
            return false;
        }
    }

    // 获得绑定的域名列表
    public function list_domain($site_id)
    {
        try{
            $ret = $this->instance->site_list_domain($site_id);
            return $ret;
        } catch( Exception $e){
            return false;
        }
    }

    public function get_attr($site,$key)
    {
        try{
            $ret = $this->instance->site_get_attr($site,$key);
            return $ret;
        } catch(Exception $e){
            return false;
        }
    }
    
    public function get_runtime_attr($site,$key)
    {
        try{
            $ret = $this->instance->site_get_runtime_attr($site,$key);
            return $ret;
        } catch(Exception $e){
            return false;
        }
    }

    public function dump_attr($site)
    {
        try {
            $ret = $this->instance->site_dump_attr($site);
            return $ret;
        } catch (Exception $e) {
            return false;
        }
    }

    public function who_is($site,$uid)
    {
        try{
            $whoami = $this->instance->site_who_is($site,$uid);
            return $whoami;
        } catch (Exception $e) {
            return false;
        }
    }

    public function add_users($site,$admin,$committer,$readonly)
    {
        try{
            $rst = $this->instance->site_add_users($site,
                $admin,$committer,$readonly);
            return $rst;
        } catch (Exception $e) {
            var_dump($e);
            return false;
        }
 
    }
            
    public function list_user($site){
        try{
            $roles = $this->instance->site_list_user($site);
            return $roles;
        } catch (Exception $e) {
            return false;
        }
    }

    public function publish($site,$data,$default)
    {
        try{
            return $this->instance->site_publish($site,$data,$default);
        } catch (Exception $e) {
            return false;
        }
    }


    public function get_published( $site )
    {
        try{
            $list = $this->instance->site_get_published($site);
            return $list;
         } catch (Exception $e) {
            return false;
        }
    }

    public function get_default_published( $site )
    {
        try{
            return $this->instance->site_get_default_published($site);
         } catch (Exception $e) {
            return false;
        }
    }

    public function get_publishing( $site )
    {
        try{
            return $this->instance->site_get_publishing($site);
         } catch (Exception $e) {
            return false;
        }
    }



    public function nginx_get_rules($site)
    {
        try{
            return $this->instance->site_nginx_get_rules($site);
         } catch (Exception $e) {
            return false;
        }
    }

    public function nginx_save_rules($site, $rules)
    {
        try{
            return $this->instance->site_nginx_save_rules($site, $rules);
         } catch (Exception $e) {
            return false;
        }
    }

    public function env($site)
    {
        try{
            $env = $this->instance->site_env($site);
            return $env;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addon_active($site,$type)
    {
        try{
            $ret =  $this->instance->site_addon_active($site,$type);
            return $ret;
        }catch(Exception $e) {
            return false;
        }
    }

    public function is_valid($site)
    {
        try{
            return $this->instance->site_is_valid($site);
        }catch(Exception $e) {
            return false;
        }
    }

    public function is_exist($site)
    {
        try{
            return $this->instance->site_is_exist($site);
        }catch(Exception $e) {
            return false;
        }
    }

    public function active($site)
    {
        try{
            return $this->instance->site_active($site);
        } catch (Exception $e) {
            return false;
        }
    }

    public function deactive($site)
    {
        try{
            return $this->instance->site_deactive($site);
        } catch (Exception $e) {
            return false;
        }
    }


    // === for agent ===
    /**
     *  agent site list
     *
     *    @param int $page
     *    @param int $limit 
     *    @param string $sort  descending/ascending
     *    @return array|boolean
     **/
    public function getList($page,$limit = 20,$sort = 'descending'){
        try{
            return $this->instance->agent_list_sites($page + 1,$limit,$sort);
        } catch (Exception $e) {
            var_dump($e);
            return false;
        }
    } // getList

    /**
     * all agent site list 
     *
     * @return array | boolean
     **/
    public function getListAll() {
        try{
            return $this->instance->agent_list_sites();
        } catch (Exception $e) {
            return false;
        }
    } // getListAll

    /**
     *     
     *
     *
     **/
    public function getSiteAliasById($site_id) {
        try{
            return $this->instance->site_get_alias($site_id);
        } catch (Exception $e) {
            return false;
        }
    }// getSiteAliasById()

    public function create_saas($uid,$name,$site_agent,$site_id)
    {
        try{
            return $this->instance->site_create_saas($uid,$name,$site_agent,$site_id);
        } catch(Exception $e) {
            return false;
        }
    }

    public function fork($site_id,$from_agent,$from_site,$from_path="/trunk",$from_ver="HEAD")
    {
        try{
            return $this->instance->site_svn_fork($site_id,$from_agent,$from_site,$from_path,$from_ver);
        } catch(Exception $e) {
            return false;
        }
    }

    public function set_blackwhite($site_id,$type = "black",$ip = array()) {
        $type = ("black" == $type)? "black" : "white";
        return $this->instance->site_set_blackwhite($site_id,$type,$ip);
    }

    public function get_blackwhite($site_id) {
        return $this->instance->site_get_blackwhite($site_id);
    }    


     public function getSiteByDomain($domain){
        return $this->instance->agent_get_site_by_domain($domain);
    }

    public function get_scm_url($site_id) {
        return $this->instance->site_get_scm_url($site_id);
    }

    public function set_php($site_id,$data) {
        return $this->instance->site_set_php($site_id,$data);
    }

    /**
     * 站点列表
     *
     * @param int    $offset // 偏移起始位置 从1开始
     * @param int    $limit  // 偏移量 
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_list($offset = 1,$limit = 20 ,$where = array(),$sort = null) {
        $offset = ($offset < 1)? 1  : intval($offset) + 1;
        $limit  = ($offset < 1)? 20 : intval($limit);
        if($sort) {
            return $this->instance->site_list($offset,$limit,$where,$sort);
        } else {
            return $this->instance->site_list($offset,$limit,$where);
        }
    } // end function get_list

    /**
     * 站点列表(所有)
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_all_list($where = array()) {
        return $this->instance->site_list($where);
    } // end function get_all_list

    /**
     * 站点数量
     *
     * @param array  $where  // 条件 array(array('status','true'),array('type','paas')) 根据 
     * @return array
     **/
    public function get_count($where = array()) {
        return $this->instance->site_count($where);
    } // end function get_count

    /**
     * 获取指定站点信息
     *
     * @param string  $site_id  // 三级域名
     * @return array
     **/
    public function get_info($site_id,$v = 1) {
        if($v == 2) {
             return $this->instance->site_get_info_v2($site_id);           
        } else {
            return $this->instance->site_get_info($site_id);
        }
    } // end function get_info

    /**
     * 设置指定站点信息
     *
     * @param string  $site_id  // 三级域名
     * @param array   $arrData
     * @return boolean
     **/
    public function set_info($site_id,$arrData) {
        return $this->instance->site_set_info($site_id,$arrData);
    } // end function get_info

    /**
	 * 添加站点
	 * 
	 * @param string $user_id
	 * @param string $site       // 要开通的站点名称
	 * @param string $package_id // 套餐ID
     * @param string $app_id     // 应用ID
	 * @return boolean
	 */
	public function add($user_id,$site,$package_id,$app_id = null) {
		$arrOption = ($app_id)? array(array("app",$app_id)) : array();
		return $this->instance->site_create($user_id,$site,$package_id,$arrOption);
	} // end function add

    /**
     * 更新指定站点varnish
     *
     * @param string  $site_id  // 三级域名
     * @return boolean
     **/
    public function purge_varnish($site_id) {
        return $this->instance->site_varnish_purge($site_id);
    } // end function purge_varnish 

} // end class
