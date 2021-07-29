<?php
require_once __DIR__ . "/lib/Web/JsonClass.php";
require_once __DIR__ . "/helper/user.php";

class jres extends \Web\JsonClass {
    public function test() {
        user::jsonValidate();
    }
}

new jres(1);