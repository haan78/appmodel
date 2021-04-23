<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/lib/Web/Session.php";

$p = new Gregwar\Captcha\PhraseBuilder(4, '1234567890');
$c = new Gregwar\Captcha\CaptchaBuilder(null, $p);
$c->build();
(new \Web\SessionDefault())->set("captcha", $c->getPhrase());
header('Content-type: image/jpeg');
$c->output();