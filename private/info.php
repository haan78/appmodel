<?php
require_once __DIR__ . "/helper/db.php";
//throw new Exception("Test1");
//session_start(); echo "<pre>\n"; print_r( $_SESSION ); echo "\n</pre>";
//echo "<pre>\n"; print_r($_COOKIE); echo "\n</pre>";
//echo "<pre>\n"; print_r($_SERVER); echo "\n</pre>";
//echo "<pre>\n"; print_r($_COOKIE); echo "\n</pre>";
//echo "<pre>\n"; var_dump( get_browser() ); echo "\n</pre>";
//phpinfo();

echo  "<xmp>\n".filter_input( INPUT_GET,"pass",FILTER_SANITIZE_SPECIAL_CHARS  )."\n</xmp>";

echo md5( $_GET["pass"] );

var_dump( filter_var( " ' or 1 = 1 " ) );

var_dump( filter_var( "haan78@gmail.com",FILTER_VALIDATE_EMAIL ) );

$result = db::mongo()->selectDatabase("Test1")->selectCollection("User")->findOne([]);

var_dump($result);