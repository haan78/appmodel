<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/lib/Web/Json.php";

use \Web\Json;
Json::perform(function() {
    $action = Web\Web::path(1);
    if ( $action == "topla" ) {
        return $_SERVER;
    } else {
        throw new Exception("Action unknown");
    }
});