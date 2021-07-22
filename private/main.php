<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/lib/Web/Template.php";

if (user::validate()) {
    $cssList = [
        "assets/chunk-vendors.css",
        "assets/main.css"
    ];
    $jsList = [
        "assets/chunk-vendors.js",
        "assets/main.js"
    ];
    \Web\Template::load($cssList, $jsList, __DIR__ . "/temps/temp2.html");
} else {
    header("Refresh:0; url=/login?s=2");
}
