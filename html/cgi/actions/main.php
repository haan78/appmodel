<?php
require_once CGI_DIR."/lib/Web/Page.php";
//\Web\Page::ErrorTemplate("/private/temps/Error.html");
\Web\Page::ScriptTemplate( CGI_DIR . "/temps/temp1.html", [
    "/main.css",
    "/vendor.js",
    "/main.js"
    ],
    ["Test"=>123]
);
