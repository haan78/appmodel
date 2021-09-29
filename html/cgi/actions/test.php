<?php
require_once __DIR__."/helper/db.php";

db::session();

echo session_id();

var_dump($_SESSION);

