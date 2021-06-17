<?php
require_once "/private/lib/Web/Web.php";
require_once "/private/helper/settings.php";
require_once "/private/helper/error.php";

use Web\Web;

$action = Web::path(0, "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    include $file;
}