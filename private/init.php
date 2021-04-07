<?php
if (!defined('ROOT')) {
    exit();
}
require_once "lib/Web/Web.php";
require_once "lib/Web/HTML.php";
require_once "lib/Web/JsonResponse.php";
require_once "vendor/autoload.php";
require_once "user.php";
require_once "page_helper.php";
require_once "settings.php";

use Web\Web;
use Web\HTML;
use Web\JsonResponse;

Web::errorHandler(function (Exception $ex) {
    page_helper::error($ex);
});
$path = Web::action();

if ($path == "") {
    $t = user::test($md);
    if ($t === user::TEST_ACCEPT) {
        page_helper::temp1(HTML::load(ROOT, "main", $md));
    } elseif ($t === user::TEST_RELOAD) {
        header("Refresh:0; url=/$path");
    } else { //TEST_REJECT
        page_helper::temp1(HTML::load(ROOT, "login", $md));
    }
} elseif ($path == "server") {
    JsonResponse::perform(function () {
        return $_SERVER;
    }, (object)["pretty" => true]);
} elseif ($path == "topla") {
    JsonResponse::perform(function ($post) {
        user::assert();
        return ($post[0] + $post[1] + 1);
    });
} elseif ($path == "logout") {
    user::sessionKill();
    header("Refresh:0; url=/");
} elseif ($path == "captcha") {
    $p = new Gregwar\Captcha\PhraseBuilder(4, '1234567890');
    $c = new Gregwar\Captcha\CaptchaBuilder(null, $p);
    $c->build();
    user::sessionSet("captcha", $c->getPhrase());
    //echo user::sessionGet("captcha");
    header('Content-type: image/jpeg');
    $c->output();
} elseif ($path == "upload") {
    include "upload.php";
} else {
    throw new Exception("There is no action like $path");
}
