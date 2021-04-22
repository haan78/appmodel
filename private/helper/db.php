<?php
require_once __DIR__ . "/../lib/MySqlTool/MySqlToolCall.php";

class db {
    private static $mongodb = null;
    public static function maria() {
        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 20);
        mysqli_real_connect($link,MARIA_HOST,MARIA_USER,MARIA_PASS,MARIA_SCHEMA,MARIA_PORT);        
        mysqli_set_charset($link, "utf8");
        return $link;
    }

    public static function prc() {
        $link = self::maria();
        require_once "lib/MySqlTool/MySqlToolCall.php";
        return new \MySqlTool\MySqlToolCall($link);
    }

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
}