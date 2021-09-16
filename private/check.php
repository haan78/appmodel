<?php

require_once __DIR__."/helper/db.php";

var_dump( db::activeUserCount("Baris") );

db::session("Baris");



var_dump($_SESSION);
