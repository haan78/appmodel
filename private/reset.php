<?php
require_once __DIR__ . "/lib/Web/Template.php";
$module = "reset";
\Web\Template::html(
    [
        "css/chunk-vendors.css",
        "css/$module.css",
        "js/chunk-vendors.js",
        "js/$module.js"
    ],
    __DIR__ . "/temps/temp1.html"
);