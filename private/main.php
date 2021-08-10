<?php
require_once __DIR__ . "/helper/user.php";

if (user::validate()) { 
    require_once __DIR__ . "/lib/Web/Template.php";
    $module = "main";
    \Web\Template::html(
        [
            "css/chunk-vendors.css",
            "css/$module.css",
            "js/chunk-vendors.js",
            "js/$module.js"
        ],
        __DIR__ . "/temps/temp1.html"
    );
} else {
    header("Refresh:0; url=/login");
}
