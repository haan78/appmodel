<?php
require_once "/private/helper/settings.php";
require_once "/private/lib/Web/Web.php";

use Web\Web;

$action = Web::path(0, "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    Web::errorHandler(function (Exception $ex) {
        require_once "/private/helper/page.php";
        page::error($ex);
    });
    include $file;
}