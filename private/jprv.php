<?php
require_once __DIR__ . "/helper/json.php";
require_once __DIR__ . "/helper/user.php";
$action = Web\Web::path(1);
json::$auth = function() {
    return true;
};
if ($action=="topla") {
    json::response(function () {
        return $_SERVER;
    });
} else {
    json::error("Action unknown");
}