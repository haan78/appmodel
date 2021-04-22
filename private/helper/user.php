<?php

require_once __DIR__ . "/../lib/Web/Webpack.php";
require_once __DIR__ . "/../lib/Web/Ticket.php";
require_once __DIR__ . "/../lib/Web/Session.php";

use \Web\Ticket;
use \Web\SessionDefault;

class User {
    public static function get() {

        $md = [];

        $user = (new SessionDefault())->get("user");
        if ($user) {
            $md["time"] = date("Y-m-d H:i:s");
            $md["user"] = $user;
            return $md;
        } else {
            return false;
        }
    }

    public static function set(): bool {
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

    public static function test_login(): bool {
        return self::get();
    }

    public static function test_ticket(): bool {
        $t = new Ticket(new SessionDefault());
        return $t->pass();
    }
}
