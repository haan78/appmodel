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
        private bool $deleteRecord = false;

        public static function init($connection,string $collectionName, bool $deleteRecord = false, int $lifeTime = 1 * 60) : void {
            $db = null;
            if ( is_string($connection) ) {
                $db = new Client($connection);
            } elseif ( $connection instanceof Client ) {
                $db = $connection;
            } else {
                throw new Exception("Connection must be connection string or MongoDbClinet");
            }
            $coll = $db->selectDatabase("test")->selectCollection($collectionName);
            $coll->createIndex(["session_id"=>1, "active"=>1]);
            $coll->createIndex(["expires"=>1, "active"=>1]);
            $handler = new MongoSession($coll,$deleteRecord);
            ini_set('session.gc_maxlifetime', $lifeTime);
            session_set_save_handler($handler, false);
            session_start();
            
        }

        public function __construct(Collection $coll, bool $deleteRecord = false)
        {
            $this->coll = $coll;
            $this->deleteRecord = $deleteRecord;
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

        public function write($id, $data) {
            if ( empty($data) ) {
                return true;
            }
            $fields = [
                "session_id"=>$id,
                "data"=>$data,
                "last_modified" =>new  UTCDateTime(),
                "life" => ini_get('session.gc_maxlifetime'),
                "name" => $this->sessionName,
                "time"=>time(),
                "active"=>true
            ]; 
            $insert = [
                "begin" => new  UTCDateTime(),
                "end" => null,
                "duration" => (-1*time())
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
                $mt = new  UTCDateTime();
                if ( $this->deleteRecord ) {
                    $this->coll->deleteOne([ 'session_id' => $id, "active" => true ]);
                } else {
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
                }
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
                if ( $this->deleteRecord ) {
                    $res = $this->coll->deleteMany(['time' => ['$lt' => $t ], "active" => true ]);
                    $c = $res->getDeletedCount();  
                                  
                } else {
                    $res = $this->coll->updateMany(['time' => ['$lt' => ($t - $maxlifetime) ], "active" => true ], ['$inc'=>["duration"=>$t],'$set'=>["active" => false, "end"=>new  UTCDateTime()]]);
                    $c = $res->getMatchedCount();                    
                }                
            } catch(Exception $ex) {
                return false;
            }
            return $c;
        }
    }
}
