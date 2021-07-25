<?php
require_once __DIR__ . "/lib/Web/Template.php";
\Web\Template::html(
    [
        "css/chunk-vendors.css",
        "css/login.css",
        "js/chunk-vendors.js",
        "js/login.js"
    ],
    __DIR__ . "/temps/temp1.html",
    ["status" => (isset($_GET["s"]) ? intval($_GET["s"]) : 0),"test"=>"test"]
);
