<?php

require_once "/private/lib/Web/PathInfo.php";
require_once "/private/lib/Web/ErrorPage.php";
require_once "/private/helper/settings.php";

use Web\ErrorPage;
use Web\PathInfo;

ErrorPage::handler("/private/temps/Error.html");
$action = (PathInfo::item(0) ? PathInfo::item(0) : "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    include $file;
} else {
    throw new Exception("File not found / $file");
}
