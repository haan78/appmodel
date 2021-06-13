<?php
require_once __DIR__ . "/lib/Web/Json.php";

use \Web\Json;
Json::$JSON_FLAGS = JSON_PRETTY_PRINT;
Json::perform(function() {
    $action = Web\Web::path(1);
    if ( $action == "server" ) {
        return $_SERVER;
    } else {
        throw new Exception("Action unknown");
    }
});