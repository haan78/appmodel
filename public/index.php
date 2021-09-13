<?php

require_once "/etc/settings.php";
require_once "/private/lib/Web/PathInfo.php";
require_once "/private/lib/Web/Page.php";

use Web\Page;
use Web\PathInfo;

Page::ErrorTemplate("/private/temps/Error.html");
$action = (PathInfo::item(0) ? PathInfo::item(0) : "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    include $file;
} else {
    throw new Exception("File not found / $file");
}
