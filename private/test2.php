<?php

session_start();

$_SESSION["count"] = ( isset($_SESSION["count"]) ? intval($_SESSION["count"]) : 0 ) + 1;

var_dump($_SESSION);
