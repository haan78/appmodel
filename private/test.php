<?php
require_once __DIR__ . "/helper/db.php";


$result = db::mongo()->selectDatabase("Test1")->selectCollection("User")->findOne(["email"=>"haan78@gmail.com"]);
echo  "<xmp>\n".var_dump($result["pass"])."\n".md5("12345")."\n</xmp>";
