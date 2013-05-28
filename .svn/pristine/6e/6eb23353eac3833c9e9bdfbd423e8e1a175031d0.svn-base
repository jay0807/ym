<?php
class yunmall_mdl_abstruct extends yunmall_model {

    private $objIndex = null; 
    private $node     = null;
    protected $token  = null;

    public function __construct($app) {
        parent::__construct($app);
        $this->node     = $this->app->getConf($this->token);
        //$this->node     =  1;
        $this->objIndex = app::get("content")->model("article_indexs");
        $this->objBody  = app::get("content")->model("article_bodys");
    } // end function __construct

    public function getOne($id) {
        if(!($arrIndex = $this->objIndex->dump(array("article_id" => $id,"node_id"=>$this->node)))) {
            return false;
        } 
        if(!($arrBody = $this->objBody->dump(array("article_id" => $id)))) {
            return false;
        }
        return array_merge($arrBody,$arrIndex);
    } // end function getOne

    public function count($filter=null){
        if(!$this->node) return 0;
        $filter["node_id"] = $this->node;
        $filter["ifpub"] = "true";
        return $this->objIndex->count($filter);
    } // end function count

    public function getList($cols='*', $filter=array(), $offset=0, $limit=-1, $orderType=null){
        if(!$this->node) return array();
        $filter["node_id"] = $this->node;
        //$filter["ifpub"] = "true";
        $arrData = $this->objIndex->getList($cols,$filter,$offset,$limit,$orderType);

        $arrResult = array();
        $arrArticle = array();
        foreach($arrData as $key => $row ) {
            $arrResult[$row["article_id"]] = $row; 
            $arrResult[$row["article_id"]]["image_id"] = null;
            $arrResult[$row["article_id"]]["link_id"] =  $row["article_id"].",".$row["title"];
            $arrArticle[] = $row["article_id"]; 
        }
        if($arrArticle) {
            $arrTemp = $this->objBody->getList("article_id,image_id",array("article_id"=>$arrArticle)); 
            foreach($arrTemp as $key => $row) {
                $arrResult[$row["article_id"]]["image_id"] = $row["image_id"];
            }
        }

        return array_values($arrResult);
    } // end function getList

    public function orderSelect() {
        return array(
            "pubtime DESC" => app::get("yunmall")->_("发布时间由新到旧"),
            "pubtime ASC" => app::get("yunmall")->_("发布时间由旧到新")
        );
    } // end function orderSelect

    public function get_schema() {
        return array (
                'columns' =>
                array (
                    'article_id' =>array (
                        'type' => 'number',
                        'required' => true,
                        'label'=> app::get('content')->_('文章ID'),
                        'pkey' => true,
                        'extra' => 'auto_increment',
                        'width' => 50,
                        'editable' => false,
                        'in_list' => true,
                        'default_in_list' => true,
                    ),
                    'title' =>array (
                        'type' => 'varchar(200)',
                        'required' => true,
                        'label' => app::get('content')->_('文章标题'),
                        'width' => 300,
                        'searchtype' => 'has',
                        'editable' => true,
                        'filtertype' => 'yes',
                        'filterdefault' => true,
                        'in_list' => true,
                        'default_in_list' => true,
                        'is_title'=>true,
                    ),
                    'type' =>array (
                        'type' => array(
                            '1' => app::get('content')->_('普通文章'),
                            '2' => app::get('content')->_('单独页'),
                            '3' => app::get('content')->_('自定义页面'),
                        ),
                        'label' => app::get('content')->_('文章类型'),
                        'required' => true,
                        'default' => 1,
                        'width' => 80,
                        'filtertype' => 'yes',
                        'filterdefault' => true,
                        'in_list' => true,
                        'default_in_list' => false,
                    ),
                    'node_id' =>array (
                        'type' => 'table:article_nodes',
                        'required' => true,
                        'label'=> app::get('content')->_('节点'),
                        'width' => 80,
                        'editable' => true,
                        'filtertype' => 'yes',
                        'filterdefault' => true,
                        'in_list' => true,
                        'default_in_list' => true,
                    ),
                    
                    'author' => array (
                        'type' => 'varchar(50)',
                        'label' => app::get('content')->_('作者'),
                        'editable' => true,
                        'searchtype' => 'has',
                        'width' => 80,
                        'filtertype' => 'yes',
                        'filterdefault' => true,
                        'in_list' => true,
                        'default_in_list' => true,
                    ),
                    'pubtime' => array(
                        'type' => 'time',
                        'label' => app::get('content')->_('发布时间（无需精确到秒）'),
                        'editable' => true,
                        'width' => 130,
                        'filtertype' => 'yes',
                        'filterdefault' => true,
                        'in_list' => true,
                        'default_in_list' => true,
                    ),
                    'uptime' =>array (
                        'type' => 'time',
                        'label' => app::get('content')->_('更新时间（精确到秒）'),
                        'editable' => false,
                        'width' => 130,
                        'in_list' => true,
                        'default_in_list' => true,
                    ),
                    'level' => array(
                        'type' => array(
                            '1' => app::get('content')->_('普通'),
                            '2' => app::get('content')->_('重要'),
                        ),
                        'label' => app::get('content')->_('文章等级'),
                        'required' => true,
                        'filtertype' => 'yes',
                        'filterdefault' => false,
                        'default' => 1,
                        'editable' => true,               
                    ),
                    'ifpub' => array(
                        'type' => 'bool',
                        'required' => true,
                        'default' => 'false',
                        'label' => app::get('content')->_('发布'),
                        'editable' => true,
                        'in_list' => true,
                        'filtertype' => 'yes',
                        'filterdefault' => false,
                        'width' => 40,
                        'default_in_list' => true,
                    ),
                    'pv' => array(
                        'type' => 'int unsigned',
                        'default' => 0,
                        'label' => 'pageview',
                        'editable' => false,
                    ),
                    'disabled' => array(
                        'type' => 'bool',
                        'required' => true,
                        'default' => 'false',
                        'editable' => true,
                    ),
              ),
              'comment' => app::get('content')->_('文章主表'),
              'index' => 
                  array (
                    'ind_node_id' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'node_id',
                      ),
                    ),
                    'ind_ifpub' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'ifpub',
                      ),
                    ),
                    'ind_pubtime' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'pubtime',
                      ),
                    ),
                    'ind_level' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'level',
                      ),
                    ),
                    'ind_disabled' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'disabled',
                      ),
                    ),
                    'ind_pv' => 
                    array (
                      'columns' => 
                      array (
                        0 => 'pv',
                      ),
                    ),
              ),
              'version' => '$Rev$',
            );
    } // end function getSchema

} // end class 
