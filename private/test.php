<?php
require_once __DIR__."/helper/db.php";

db::session("Baris");

$_SESSION["test"] = "Selam";


var_dump($_SESSION);
var_dump(session_id());
