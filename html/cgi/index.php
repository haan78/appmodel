<?php
require_once "/etc/settings.php";
require_once __DIR__ . "/lib/Web/PathInfo.php";

use Web\PathInfo;


$action = (PathInfo::item(0) ? PathInfo::item(0) : "main");

$file = __DIR__ . "/action/$action.php";
if (file_exists($file)) {
    include $file;
} else {
    die("File not found $file");
}
