<?php
if (!defined('ROOT')) {
    exit();
}
require_once __DIR__ . '/vendor/autoload.php';
require_once "lib/Web/Web.php";
require_once "lib/Web/Session.php";
require_once "user.php";
require_once "db.php";
require_once "json.php";
require_once "page.php";
require_once "settings.php";

use Web\Web;

Web::errorHandler(function (Exception $ex) {
    include "error.php";
});

$action = Web::path(0);

if ($action == "" || $action == "main") {
    include "main.php";
} elseif ($action == "welcome") {
    include "welcome.php";
} elseif ($action == "login") {
    include "login.php";
} elseif ($action == "reset") {
    include "reset.php";
} elseif ($action =="activate") {
    include "activate.php";
} elseif ($action == "register" ) {
    include "register.php";
} elseif ($action == "logout") {
    include "logout.php";
} elseif ($action == "json_pup") {
    include "json_pup.php";    
} elseif ($action == "json_prv") {
    include "json_prv.php";    
} elseif ($action == "captcha") {
    include "captcha.php";
} elseif ($action == "upload") {
    include "upload.php";
} elseif ($action == "info") {
    phpinfo();
} else {
    throw new Exception("There is no action like $path");
}
