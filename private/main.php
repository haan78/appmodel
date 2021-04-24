<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/helper/page.php";
if ( user::validate() ) {
    page::template("main",[ "message"=>"if you want to add meta data use this array" ]);
} else {
    header("Refresh:0; url=/welcome");
}
