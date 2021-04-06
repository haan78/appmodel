<?php
if ( !defined('ROOT') ) {
    exit();
}
require_once "settings.php";
require_once "lib/Web/Web.php";
require_once "user.php";
require_once "vendor/autoload.php";
require_once "page.php";

use Web\Web;

Web::init(function ($path) {
    if ($path == "") {
        user::test(function($md){
            page::load(ROOT,"main",$md);
        },function($md){
            page::load(ROOT,"login",$md);
        },$path);
    } elseif ($path == "topla") {
        Web::jsonResponse(function ($post) {
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
        echo "What! $path";
    }
}, function (Exception $ex) {
    include "error.php";
});
