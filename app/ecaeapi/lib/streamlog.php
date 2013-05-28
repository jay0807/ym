<?php
#ini_set('mongo.native_long',1);

    class ecaeapi_streamlog {

    
        public function conn($type,$time=null){
            if( !$time ) $time = time();
            $arr = ecae_agent_api()->streamlog($type);
            #$arr = array('user'=>'streamlog','pwd'=>'streamlogpassword','host'=>'ecae-101.dev','port'=>'12030','interval'=>3600);
            $user = $arr['user'];
            $pwd  = $arr['pwd'];
            $host = $arr['host'];
            $port = $arr['port'];
            $interval = $arr['interval'];
            $dbname = 'ecae_'.$type;
            $tbname = $dbname.'_'.floor($time/$interval);
            $dbstr = "mongodb://{$user}:{$pwd}@{$host}:{$port}/{$dbname}";
            $con = new Mongo($dbstr);
            $db = $con->selectDB($dbname);
            $collection = new MongoCollection($db, $tbname);
            return $collection;
        }

    }
