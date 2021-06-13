<?php

require_once __DIR__ . "/helper/user.php";
require_once __DIR__ . "/helper/page.php";
page::template("welcome", ["com" => "login"]);