<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/helper/page.php";
$message = "";
if (user::login($message)) {
    //var_dump($_SESSION); var_dump($_COOKIE); exit();
    header("Refresh:0; url=/main");
} else {
    page::template("welcome",["com"=>"login", "message"=>$message]);
}
