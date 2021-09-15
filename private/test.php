<?php
require_once __DIR__."/helper/db.php";

db::session();
$_SESSION["test"] = "Selam";


var_dump($_SESSION);
