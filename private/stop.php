<?php
require_once __DIR__."/helper/db.php";

db::session("Baris");

var_dump($_SESSION);

session_destroy();
unset($_SESSION);

var_dump($_SESSION);