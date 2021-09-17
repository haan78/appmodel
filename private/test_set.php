<?php
require_once __DIR__."/helper/db.php";

db::session();

db::setUserID("Test User");

$_SESSION["deneme"]=123;

var_dump($_SESSION);