<?php
if (!defined('ROOT')) {
    exit();
}
require_once "lib/Web/Web.php";
require_once "lib/Web/HTML.php";
require_once "lib/Web/JsonResponse.php";
require_once "vendor/autoload.php";
require_once "user.php";
require_once "db.php";
require_once "page_helper.php";
require_once "settings.php";

use Web\Web;
use Web\HTML;
use Web\JsonResponse;

Web::errorHandler(function (Exception $ex) {
    page_helper::error($ex);
});
$path = Web::path()[0];

if ($path == "") {
    $t = user::test($md);
    if ($t === user::TEST_ACCEPT) {
        page_helper::temp1(HTML::generate(ROOT, "main", $md));
    } elseif ($t === user::TEST_RELOAD) {
        header("Refresh:0; url=/$path");
    } else { //TEST_REJECT
        page_helper::temp1(HTML::generate(ROOT, "login", $md));
    }
    db::log("Test1","LogHTML",["test"=>$t,"session"=>$_SESSION]);
} elseif ($path == "server") {
    JsonResponse::perform(function () {
        if (user::testTicket()) {
            db::log("Test1","LogAJAX",["method"=>"server","session"=>$_SESSION]);   
            return $_SERVER;
        } else {
            throw new Exception("You shall not pass!");
        }            
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
} elseif($path == "cros") {
    include "cros.php";
} elseif ($path=="info") {
    phpinfo();
} elseif($path=="mongo") {
    include "mongo.php";
}else {
    throw new Exception("There is no action like $path");
}
