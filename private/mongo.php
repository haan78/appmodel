<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Web/MongoUpload.php';

$id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_SPECIAL_CHARS);
$mongo = new \MongoDB\Client("mongodb://root:12345@mongodb");
$db = $mongo->selectDatabase("Test1");
$bucket = $db->selectGridFSBucket([ "disableMD5"=> false, "bucketName"=>"upload" ]);

if ( !empty($_FILES) ) {
    //\Web\MongoUpload::$mimeTypes = [];
    $id = \Web\MongoUpload::save($bucket);
    $data = $_POST;
    $data["_id"] = $id;
    print_r($data);
} elseif(!is_null($id)) {
    #\Web\MongoUpload::download($bucket,$id);
    $c = \Web\MongoUpload::get($bucket,$id,$ft);
    header("Content-Type: ".$ft);
    echo $c;
} else {
    throw new Exception("Nothing to do!");
}

