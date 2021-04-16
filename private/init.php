<?php
if (!defined('ROOT')) {
    exit();
}
require_once "lib/Web/Web.php";
require_once "lib/Web/Vue.php";
require_once "vendor/autoload.php";
require_once "user.php";
require_once "db.php";
require_once "page_helper.php";
require_once "settings.php";

use Web\Web;
use Web\Vue;

Web::errorHandler(function (Exception $ex) {
    page_helper::errorHTML($ex);
});

$path = isset(Web::pathinfo()[0]) ? Web::pathinfo()[0] : "";

if ($path == "") {
    $t = user::testForLogin($md, $path);
    if ($t === user::TEST_ACCEPT) {
        page_helper::temp1(Vue::scriptList(ROOT, "main", $md));
    } elseif ($t === user::TEST_RELOAD) {
        header("Refresh:0; url=/$path");
    } else { //TEST_REJECT
        page_helper::temp1(Vue::scriptList(ROOT, "login", $md));
    }
    db::log("Test1", "LogHTML", ["test" => $t, "session" => $_SESSION]);
} elseif ($path == "server") {
    page_helper::json(Web::exec(function () {
        return $_SERVER;
    }));
    db::log("Test1", "LogAJAX", ["method" => "server", "session" => $_SESSION]);
} elseif ($path == "topla") {
    page_helper::json(Web::exec(function ($post) {
        return ($post[0] + $post[1] + 1);
    }));
} elseif ($path == "logout") {
    Web::sessionKill();
    header("Refresh:0; url=/");
} elseif ($path == "captcha") {
    $p = new Gregwar\Captcha\PhraseBuilder(4, '1234567890');
    $c = new Gregwar\Captcha\CaptchaBuilder(null, $p);
    $c->build();
    Web::sessionSet("captcha", $c->getPhrase());
    //echo user::sessionGet("captcha");
    header('Content-type: image/jpeg');
    $c->output();
} elseif ($path == "upload") {
    include "upload.php";
} elseif ($path == "cros") {
    include "cros.php";
} elseif ($path == "info") {
    phpinfo();
} elseif ($path == "test") {
    echo "<pre>" . PHP_EOL;
    print_r($_SERVER);
    echo PHP_EOL . "</pre>";
} elseif ($path == "mongo") {
    include "mongo.php";
} else {
    throw new Exception("There is no action like $path");
}
