<?php
$action = Web\Web::path(1);

if ($action=="server") {
    json::response(function () {
        return $_SERVER;
    });
} else {
    json::error("Action unknown");
}