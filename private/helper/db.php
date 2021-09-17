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
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        \MongoTools\MongoSession::init( $coll );
    }

    public static function setUserID(string $user_id) : void {
        \MongoTools\MongoSession::setUserID($user_id);
    }

    public static function activeUserCount(string $user_id) {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        return \MongoTools\MongoSession::userCount( $coll,$user_id );
    }

    public static function clientValidate(string &$message) : bool {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        return \MongoTools\MongoSession::clinetValidate($coll,$message);
    }

    public static function test() {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        $t = time();
        $jsf = "function() { return this.time > $t - this.life; }";
        $w = ['$where' => $jsf];
        $e = ['$expr' => [ '$lt'=> [ [ '$add'=> ['$time','$life'] ], $t ] ], "active"=>true ];
        $cursor = $coll->find($e);
        var_dump($cursor);
        foreach ($cursor as $doc) {
            echo "<pre>\n";
            var_dump($doc);
            echo "\n</pre>
            <br/>
            ".($doc["time"] -($t-$doc["life"]))."
            <hr/>";
        }
    }
}