<?php
require_once __DIR__ . "/../lib/Web/Ticket.php";
require_once __DIR__ . "/../lib/Web/Session.php";
use \Web\Web;
use \Web\Ticket;
use \Web\SessionDefault;

class json {

    public static $auth = false;
    private static function print($result) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($result);
    }
    public static function error(string $message) {
        $result = ["success" => false, "data" => ["message" => $message]];
        static::print($result);
    }
    public static function response(callable $fnc) {
        $t = new Ticket(new SessionDefault());
        if ($t->pass()) {
            if ( is_callable(static::$auth) ) {
                if ( call_user_func(static::$auth) ) {
                    static::print(Web::exec($fnc));
                } else {
                    static::error("Auth Fail!");
                }
            } else {
                static::print(Web::exec($fnc));
            }          
        } else {
            static::error("Ticket problem!");
        }
    }
}