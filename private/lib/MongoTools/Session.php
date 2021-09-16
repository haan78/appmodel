<?php

namespace MongoTools {

    use Exception;
    use \SessionHandlerInterface;
    use \MongoDB\Collection;
    use \MongoDB\BSON\UTCDateTime;
    use \MongoDB\Client;

    class MongoSession implements SessionHandlerInterface {
        private $savePath;
        private $sessionName;
        private Collection $coll;

        private static bool $initialized = false;
        private static string $USER_ID; 

        public static function init(Collection $coll,string $user_id, int $lifeTime = 1 * 60) : void {
            if ( self::$initialized ) {
                throw new Exception("This method has already been performed");
            } elseif ( isset($_SESSION) ) {
                throw new Exception("Session has already started");
            }
            $name = $coll->getCollectionName();
            $coll->createIndexes([
                [ "key"=>[ "time"=>1], "name"=>$name."_ind_1" ],
                [ "key"=>[ "session_id"=>1, "active"=>1], "name"=>$name."_ind_2" ],
                [ "key"=>[ "expires"=>1, "active"=>1 ], "name"=>$name."_ind_3" ],
                [ "key"=>[ "user_id"=>1, "active"=>1 ], "name"=>$name."_ind_4" ]
            ]);
            $handler = new MongoSession($coll);
            ini_set('session.gc_maxlifetime', $lifeTime);
            self::$USER_ID = $user_id;
            session_set_save_handler($handler, false); 
            session_start(); 
            self::$initialized = true;      
        }

        public static function clinetValidate(Collection $coll) : int {
            if ( !self::$initialized ) {
                throw new Exception(__CLASS__."::init function must be called first" );
            }
            self::getClientInfo($agent,$address);
            $session_id = session_id();

            $result = $coll->findOne([ "session_id"=>$session_id, "active"=>true ],["sort" => ["time"=>-1] ]);
            if ( !is_null($result) ) {
                if ( $result["address"] == $address ) {
                    if ( $result["agent"] == $agent ) {
                        return 0;
                    } else {
                        return 3;
                    }
                } else {
                    return 2;
                }
            } else {
                return 1;
            }
        }

        public static function userCount(Collection $coll,string $user_id) : int {
            if ( !self::$initialized ) {
                throw new Exception(__CLASS__."::init function must be called first" );
            }
            return $coll->count(["user_id"=>$user_id, "active"=>true]);
        }

        public function __construct(Collection $coll)
        {
            $this->coll = $coll;
        }

        public function open($savePath, $sessionName) {
            $this->savePath = $savePath;
            $this->sessionName = $sessionName;

            $this->gc( ini_get('session.gc_maxlifetime') );

            return true;
        }

        public function close() {
            return true;
        }

        public function read($id) {
            $result = $this->coll->findOne([ "session_id"=>$id, "active"=>true ]);
            if ( !is_null($result) && !empty($result) && $result["data"] ) {
                return (string)$result["data"];
            } else {
                return "";
            }
        }

        private static function getClientInfo(&$agent,&$address) : void {
            $agent = "";
            $address = "";
            if ( isset($_SERVER) ) {
                if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
                    $agent = trim($_SERVER['HTTP_USER_AGENT']);
                }
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $address = trim(explode(",",$_SERVER["HTTP_X_REAL_IP"])[0]);
                } elseif ( isset($_SERVER["HTTP_X_REAL_IP"]) ) {
                    $address = trim($_SERVER["HTTP_X_REAL_IP"]);
                } elseif ( isset($_SERVER["REMOTE_ADDR"]) ) {
                    $address = trim($_SERVER["REMOTE_ADDR"]);
                }
            }
        }   

        public function write($id, $data) {
            if ( empty($data) ) {
                return true;
            }
            $fields = [
                "session_id"=>$id,
                "data"=>$data,
                "last_modified" =>new UTCDateTime(),
                "life" => ini_get('session.gc_maxlifetime'),
                "name" => $this->sessionName,
                "time"=>time(),
                "active"=>true
            ]; 
            self::getClientInfo($agent,$address);
            $insert = [
                "user_id"=>static::$USER_ID,
                "begin" => new UTCDateTime(),
                "end" => null,
                "duration" => (-1*time()),
                "agent" => $agent,
                "address" => $address
            ];           
            try {
                $this->coll->updateOne(["session_id"=>$id, "active"=>true ], [ '$set'=>$fields, '$setOnInsert'=>$insert ], array("upsert"=>true));
            } catch(Exception $ex) {
                return false;
            }            
            return true;
        }

        public function destroy($id) {
            try {
                $mt = new UTCDateTime();
                $this->coll->updateOne([ 
                    'session_id' => $id,
                    "active" => true 
                ],
                [
                    '$inc'=>[
                        "duration"=>time()
                    ],
                    '$set'=>[
                        "active" => false,
                        "end" => $mt
                    ]
                ]);
            } catch(Exception $ex) {
                return false;
            }            
            return true;
        }

        public function create_sid() : string {
            return uniqid(date('YmdHis')."-");
        }

        public function validate_sid(string $anahtar): bool {
            return true;
        }

        public function gc($maxlifetime) {            
            $t = time();
            $c = 0;
            try {
                $res = $this->coll->updateMany(['time' => ['$lt' => ($t - $maxlifetime) ], "active" => true ], ['$inc'=>["duration"=>$t],'$set'=>["active" => false, "end"=>new  UTCDateTime()]]);
                $c = $res->getMatchedCount();                
            } catch(Exception $ex) {
                return false;
            }
            return $c;
        }
    }
}
