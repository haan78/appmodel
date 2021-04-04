<?php

require_once "lib/Web/AuthUser.php";

class User extends \Web\AuthUser {
    protected static function get(stdClass &$md) : bool {       
        if (self::sessionGet("user",false)) {
            $md->time = date("Y-m-d H:i:s");            
            $md->user = self::sessionGet("user",false);
            return true;
        } else {
            return false;
        }
    }

    protected static function set(stdClass &$md) : bool {
        if (isset($_POST["user"])) {
            return true;
        } else {
            $md->message = "Wrong way!";
            return false;
        }
    }
}