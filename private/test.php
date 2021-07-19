<?php
require_once __DIR__ . "/lib/Web/Template.php";
$cssList = [
    "assets/chunk-vendors.css"
];
$jsList = [
    "assets/chunk-vendors.js",
    "assets/test.js"
];
\Web\Template::load($cssList, $jsList, __DIR__ . "/temps/temp1.html", ["Hello"=>"World"]);
