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

    public static function session(string $user_id) : void {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        \MongoTools\MongoSession::init( $coll,$user_id );
    }

    public static function activeUserCount(string $user_id) {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        return \MongoTools\MongoSession::userCount( $coll,$user_id );

    }
}