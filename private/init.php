<?php
if (!defined('ROOT')) {
    exit();
}
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/lib/Web/Web.php";
require_once __DIR__ ."/lib/Web/Session.php";
require_once __DIR__ ."/helper/user.php";
require_once __DIR__ ."/helper/db.php";
require_once __DIR__ ."/helper/json.php";
require_once __DIR__ ."/helper/page.php";
require_once __DIR__ ."/helper/settings.php";

use Web\Web;

$action = Web::path(0, "main");

$file = __DIR__ . "/$action.php";
if (file_exists($file) && $file != __FILE__) {
    Web::errorHandler(function (Exception $ex) {
        page::error($ex);
    });
    include $file;
}