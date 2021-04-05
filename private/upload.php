<?php


header("Content-Type: application/json; charset=utf8");
echo json_encode( [ "POST"=> $_POST, "FILES"=>$_FILES ] );