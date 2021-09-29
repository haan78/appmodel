<?php

require_once __DIR__."/lib/Web/Page.php";
$module = "reset";
\Web\Page::ErrorTemplate("/private/temps/Error.html");
\Web\Page::ScriptTemplate( __DIR__ . "/temps/temp1.html", [
    "css/chunk-vendors.css",
    "js/chunk-vendors.js",
    "js/$module.js"
    ],
    ["Test"=>123]
);