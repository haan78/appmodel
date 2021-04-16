<?php

require_once "lib/Web/AuthUser.php";
use \Web\Web;
use \Web\AuthUser;

class User extends AuthUser {
    protected static function get(stdClass &$md) : bool {      
        if (Web::sessionGet("user",false)) {
            $md->time = date("Y-m-d H:i:s");            
            $md->user = Web::sessionGet("user",false);
            return true;
        } else {
            return false;
        }
    }

    protected static function set(stdClass &$md) : bool {
        if (isset($_POST["user"])) {
            Web::sessionSet("user",$_POST["user"]);
            return true;
        } else {
            $md->message = "Wrong way!";
            return false;
        }
    }

    public static function assert() {
        $md = new stdClass();
        if ( !self::get($md) ) {
            throw new Exception("Authentication failed");
        }
    }
}