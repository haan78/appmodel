<?php
require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/helper/page.php";
user::clear();
page::template("welcome",["com"=>"forgot"]);