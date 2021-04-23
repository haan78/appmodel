<?php
require_once __DIR__ . "/helper/user.php";
user::kill();
header("Refresh:0; url=/welcome");