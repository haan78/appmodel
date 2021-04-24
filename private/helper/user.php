<?php

require_once __DIR__ . "/../lib/Web/Session.php";

use \Web\SessionDefault;

class User {
    public static function validate() : bool {

        $user = (new SessionDefault())->get("user");
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public static function login(): bool {
        if (isset($_POST["user"])) {
            $s = new SessionDefault();
            $s->clear();
            $s->set("user", $_POST["user"]);
            return true;
        } else {
            return false;
        }
    }

    public static function clear() {
        $s = new SessionDefault();
        $s->clear();
    }

    public static function kill() {
        $s = new SessionDefault();
        $s->kill();
    }
}
