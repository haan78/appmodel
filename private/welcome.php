<?php
require_once __DIR__ . "/lib/Web/Template.php";
$cssList = [
    "assets/chunk-vendors.css",
    "assets/welcome.css"
];
$jsList = [
    "assets/chunk-vendors.js",
    "assets/welcome.js"
];
\Web\Template::load($cssList, $jsList, __DIR__ . "/temps/temp1.html");