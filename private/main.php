<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/helper/page.php";
$md = user::get();
if ( !is_array($md) ) {
    header("Refresh:0; url=/welcome");
    return;
}
page::vuePage("main",$md);