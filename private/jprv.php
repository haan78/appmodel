<?php
require_once __DIR__ . "/helper/json.php";
$action = Web\Web::path(1);
json::$auth = is_array(user::get());
if ($action=="topla") {
    json::response(function () {
        return $_SERVER;
    });
} else {
    json::error("Action unknown");
}