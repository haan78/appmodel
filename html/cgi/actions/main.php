<?php
require_once CGI_DIR."/lib/Web/Page.php";
$module = "reset";
//\Web\Page::ErrorTemplate("/private/temps/Error.html");
\Web\Page::ScriptTemplate( CGI_DIR . "/temps/temp1.html", [
    "css/chunk-vendors.css",
    "js/chunk-vendors.js",
    "js/$module.js"
    ],
    ["Test"=>123]
);
