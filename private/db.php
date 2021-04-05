<?php
require_once "lib/MySqlTool/MySqlToolCall.php";

class db {
    public static function connection(?string $schema = DB_SCHEMA) {
        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 20);
        mysqli_real_connect($link);
        if ( !is_null($schema) ) {
            mysqli_select_db($link, $schema);
        }        
        mysqli_set_charset($link, "utf8");
        return $link;
    }

    public static function adapter() : \MySqlTool\MySqlToolCall {
        $link = self::connection();
        $c = new \MySqlTool\MySqlToolCall($link);
        return $c;
    }
}