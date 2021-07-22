<?php
require_once __DIR__ . "/lib/Web/Template.php";
$cssList = [
    "assets/chunk-vendors.css",
    "assets/login.css"
];
$jsList = [
    "assets/chunk-vendors.js",
    "assets/login.js"
];
$status = ( isset($_GET["s"]) ? intval($_GET["s"]) : 0 );
\Web\Template::load($cssList, $jsList, __DIR__ . "/temps/temp1.html",["status" => $status]);
