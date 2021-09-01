<?php
require_once __DIR__ . "/lib/Web/JsonClass.php";
require_once __DIR__ . "/helper/user.php";

class jres extends \Web\JsonClass {

    protected function auth(string $method, callable $abort): void
    {
        if ( $method == "test" ) {
            $abort("olmadi",["id"=>123]);
        }
    }

    public function test() {
        return true;
    }
}

new jres(1);