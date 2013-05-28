<?php
class partner_mdl_log extends dbeav_model{
    public function __construct($app) {
        parent::__construct($app);

        $this->log_url = "http://192.168.65.215:8098/solr/test31";
        
        // 如果有设置    
        if(defined("SITE_LOG_URL")) {
            $this->log_url = constant("SITE_LOG_URL");
        } elseif($arrData = $this->app->getConf("log")) {
            //$this->log_url = $arrData['url']."/".$arrData['token'];
            $this->log_url = $arrData['url'];
        }
    }

    public function count($filter=null){
        return $this->_count($filter);
    } // end function count

    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        $arrData = $this->_getList($cols,$filter,$offset,$limit,$orderType);
        return $arrData;
        /*
        $arrResult = array();
        foreach($arrData as $key=>$row) {
            $arrResult[] = array_merge(array("id"=>$row["id"]),$row['fields']);
        }
        return $arrResult;*/
    }// end function getList
    
    public function get_schema(){
        $schema = array (
            'columns' => array (
               'id' =>array (
                    'type' => 'int(10)',
                    'label' => app::get('appstore')->_('日志ID'),
                    'required' => true,    
                    'pkey' => true,  
                ),
                'log_txt'=>array(
                    'type' => 'varchar(255)',
                    'label' => app::get('appstore')->_('日志内容'),
                    'width' => 300,
                    'filtertype' => 'has',
                    'searchtype' => 'has',
                    'orderby'=>false,
                    'filterdefault' => true,
                ),
                'version'=>array(
                    'type' => 'varchar(255)',
                    'label' => app::get('appstore')->_('发布版本'),
                    'filtertype' => 'has',
                    'filterdefault' => true,
                ),
                'addon'=>array(
                    'type'=>array(
                        'nginx'=>"访问日志",
                        'php'=>"php",
                        'ecae'=>"站点管理日志",
                    ),
                    'label' => app::get('appstore')->_('addon'),
                    'filtertype' => 'custom',
                    'filterdefault' => true,
                ),
                'level_txt'=>array(
                    'type'=>array(
                        'info'=>"info",
                        'notice'=>"notice",
                        'error'=>"error",
                        'warning'=>"warning",
                        'debug'=>"debug",
                    ),
                    'width'=>80,
                    'label' => app::get('appstore')->_('类型'),
                    'filtertype' => 'custom',
                    'orderby'=>false,
                    'filterdefault' => true,
                ),
                'd_date'=>array(
                    'type'=>'time',
                    'label' => app::get('appstore')->_('生成时间'),
                    'orderby'=>false,
                    'filtertype' => 'time',
                ),
            ),
            'idColumn' =>'id',
            'in_list' => array (
                1 => 'level_txt',
                2 => 'd_date',
                3 => 'log_txt',
            ),
            'default_in_list' => array (
                1 => 'level_txt',
                2 => 'd_date',
                3 => 'log_txt',
            ),
        );
        return $schema;
    } // end function getSchema

    // == fetch log data====================================================
    private $_data = array();
    public function getData($arrFilter,$offset = 0,$limit = 1,$sort="key") {
        if(!$query = $this->_filter($arrFilter)) return false;
        $pos = ($offset)? "&pos=".$offset : "";
        $source_url    = $this->log_url.$query.$pos."&limit=".$limit;
//        $source_url    = $this->log_url.$query."&presort=".$sort."&start=".$offset."&rows=".$limit."&wt=json";
        $hash = md5($source_url);
        if(!isset($this->_data[$hash])) {
            $source_url = str_replace(" ","%20",$source_url);
            $content = file_get_contents($source_url);
            if(empty($content)) return false;

            //$arrData = json_decode($content,1);
            //$this->_data[$hash] = $arrData['response'];
            $this->_data[$hash] = $this->_tidy_data($content);
        }
        return $this->_data[$hash];
    } // end function getData

    public function _tidy_data($strLog) {
        $strLog = trim($strLog);
        if(empty($strLog)) return "";
        $arrResult = array();
        $arrData = explode("\n", $strLog);
        foreach($arrData as $row){
            $arrRow = explode("\t", trim($row));
            $id   = array_shift($arrRow);            // row[0] 为日志ID

            $arrRow = explode(" ", implode(" ",$arrRow));
            $date = strtotime(array_shift($arrRow)); // row[0] 为日志时间
            $log  = trim(implode(" ", $arrRow));     // 其它的都规为日志内容

            $arrResult['data'][] = array(
                "id"      => $id,
                "log_txt" => $log,
                "d_date"  => $date
            );
            if(!isset($arrResult['page']['pre'])) $arrResult['page']['pre'] = $id;
            $arrResult['page']['next'] = $id - 1;  //最后一个
        }
        return $arrResult;
    } // end function _tidy_data


    private function _getList($cols = '*',$arrFilter = null,$offset = 0,$limit = -1,$sort = "key") {
        if($arrData = $this->getData($arrFilter,$offset,$limit)) return $arrData;
        return false;
    } // end function _getList

    private function _count($arrFilter = null) {
        if($arrData = $this->getData($arrFilter)) return count($arrData['data']);
        return 0;
    } // end fucntion _count

    public function _filter($filter) {
        // 如果没有指定site_id
        $arrFilter = array();
        if(!isset($filter['site_id']) || empty($filter['site_id'])) {
            return false;
        } else {
            $arrFilter["site_id"] = $filter['site_id']; 
        }

        if(!isset($filter['search_type']) || empty($filter['search_type'])) {
            $arrFilter['addon'] = "ecae";
        } else {
            $arrFilter['addon'] = $filter['search_type'];
        }
        // 和version有关
        if(in_array($arrFilter['addon'],array("ecae","rdc"))) { // 这里的处理是不需要发布版的日志
            $arrFilter['version'] = "noversion";
        } elseif(isset($filter['version']) && !empty($filter['version'])) {
            $arrFilter['version'] = $filter['version'];
        } else {
            return false;
        }

        // 和version有关
        if(in_array($arrFilter['addon'],array("ecae"))) { // 这里的处理是不需要发布版的日志
            $arrFilter['level_txt'] = "info";
        } elseif(isset($filter['type']) && !empty($filter['type'])) {
            $arrFilter['level_txt'] = $filter['type'];
        } else {
            return false;
        }

        if($filter['time']){
            $arrFilter['date'] = '&date='.date("Y-m-d",strtotime($filter['time']));
        }else{
            $arrFilter['date'] = '&date='.date("Y-m-d", time());
        }

        if(in_array($arrFilter['addon'],array("ecae")) ){
            $arrFilter['date'] = "";
        } // 管理日志读取所有

        // 查询内容
        if(isset($filter['content']) && !empty($filter['content'])) {
            $arrFilter['search'] = '&filter='.preg_replace("/[^A-Za-z0-9-_]/isU", "", $filter['content']);
            //$query .= " AND log_txt:".$filter['content'];
            unset($filter['content']);
        } else {
            $arrFilter['search'] = "";
        }
       
        $arrFilter1 = array();
        foreach($arrFilter as $key=>$row) {
            $arrFilter1['{'.$key.'}'] = $row;
        }

        return str_replace(array_keys($arrFilter1),array_values($arrFilter1),"/{site_id}/{version}/{addon}/{level_txt}/?ecae{search}{date}");
    } // end function _filter
    
}// end class
