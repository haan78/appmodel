<?php
require_once "/vendor/autoload.php";
require_once __DIR__."/../lib/MongoTools/Session.php";

class db {
    private static $mongodb = null;

    public static function mongo() {
        if (is_null(self::$mongodb)) {
            self::$mongodb = new \MongoDB\Client(MONGO_CONNECTION_STRING);
        }
        return self::$mongodb;
    }

    public static function log(string $db, string $coll, array $logData) {
        $data = $logData;
        $data["localTime"] = date("Y-m-d H:i:s");
        $data["remoteAddr"] = ( isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null );
        self::mongo()->selectDatabase($db)->selectCollection($coll)->insertOne($data);
    }

    public static function session() : void {
        \MongoTools\MongoSession::init( "mongodb://root:12345@mongodb","session" );
    }

    public static function stop($id) {
        $mt = new  \MongoDB\BSON\UTCDateTime( round(microtime(true)));
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        $coll->updateOne([ 
            'session_id' => $id,
            "active" => true 
        ],
        [
            '$inc' => [
                "duration"=>-1*time()
            ],
            '$set'=>[
                "active" => false,
                "end" => $mt
            ]
        ]);
    }

    public static function stop2() {
        $mt = new  \MongoDB\BSON\UTCDateTime( round(microtime(true)));
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        $coll->updateOne([ 
            'expires' => ['$lt' => time() ],
            "active" => true 
        ],
        [
            '$inc' => [
                "duration"=>-1*time()
            ],
            '$set'=>[
                "active" => false,
                "end" => $mt
            ]
        ]);
    }
}