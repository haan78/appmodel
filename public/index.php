<?php
require_once "/etc/settings.php";
require_once "/private/lib/Web/PathInfo.php";


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

use Web\PathInfo;


$action = (PathInfo::item(0) ? PathInfo::item(0) : "main");

$file = "/private/$action.php";
if (file_exists($file)) {
    include $file;
} else {
    die("File not found /private/$file");
}
