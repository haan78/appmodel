<?php
$module = "reset";
\Web\Page::ScriptTemplate( __DIR__ . "/temps/temp1.html", [
    "css/chunk-vendors.css",
    "js/chunk-vendors.js",
    "js/$module.js"
    ],
    ["Test"=>123]
);