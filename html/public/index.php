<?php
require_once "/html/settings.php";
require_once CGI_DIR . "/lib/Web/PathInfo.php";

use Web\PathInfo;


$action = (PathInfo::item(0) ? PathInfo::item(0) : "main");

$file = CGI_DIR . "/actions/$action.php";
if (file_exists($file)) {
    include $file;
} else {
    die("File not found $file");
}