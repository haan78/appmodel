<?php
define("HTTP_ROOT",__DIR__); //=> /app

require_once "/private/lib/Web/Web.php";
require_once "/private/helper/user.php";
require_once "/private/helper/db.php";
require_once "/private/helper/json.php";
require_once "/private/helper/page.php";
require_once "/private/helper/settings.php";

use Web\Web;

$action = Web::path(0, "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    Web::errorHandler(function (Exception $ex) {
        page::error($ex);
    });
    include $file;
}