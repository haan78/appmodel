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

    public static function session() : void {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        \MongoTools\MongoSession::init( $coll );
    }

    public static function setUserID(string $user_id) : void {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        \MongoTools\MongoSession::setUserID($coll,$user_id);
    }

    public static function activeUserCount(string $user_id) {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        return \MongoTools\MongoSession::userCount( $coll,$user_id );
    }

    public static function clientValidate(string &$message) : bool {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        return \MongoTools\MongoSession::clinetValidate($coll,$message);
    }

    public static function log(array $data) : void {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("log");
        $coll->insertOne([
            "time" => new \MongoDB\BSON\UTCDateTime(),
            "session_id" => session_id(),
            "session_data" => (  isset($_SESSION) ? $_SESSION : null ),
            "data" => $data
        ]);
    }

    public static function test() {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        $t = time();
        $e = ['$expr' => [ '$lt'=> [ [ '$add'=> ['$time','$life'] ], $t ] ], "active"=>true ];
        $cursor = $coll->find($e);
        var_dump($cursor);
        echo "<hr/>";
        foreach ($cursor as $doc) {
            echo "<pre>\n";
            var_dump($doc);
            echo "\n</pre>
            <br/>
            ".($doc["time"] -($t-$doc["life"]))."
            <hr/>";
        }
        echo "<hr/>";
    }

    public static function test2(string $id = "") {
        $coll = self::mongo()->selectDatabase("test")->selectCollection("session");
        $aggr = [
            [ '$set' => [
                    "duration"=>[ '$subtract'=>[ '$$NOW','$last_modified' ] ],
                    "active" => false,
                    "end" => new \MongoDB\BSON\UTCDateTime()
                ]
            ]
        ];
        if (!empty($id)) {
            $result = $coll->updateOne([
                '_id' => new \MongoDB\BSON\ObjectId($id),
                "active"=>true
            ],$aggr);
            return $result->getMatchedCount();
        } else {
            $result = $coll->updateMany(
                ['$expr' => [ '$lt'=> [ [ '$add'=> ['$last_modified','$life'] ], '$$NOW' ] ], "active"=>true ],$aggr
            );
            return $result->getMatchedCount();
        }
    }
}