<?php
require_once __DIR__ . '/lib/Web/MongoUpload.php';
require_once __DIR__ . "/helper/db.php";


if ( !empty($_FILES) ) {
    //\Web\MongoUpload::$mimeTypes = [];
    $bucket = db::mongo()->selectGridFSBucket([ "disableMD5"=> false, "bucketName"=>"upload" ]);
    $id = \Web\MongoUpload::save($bucket);
    $data = $_POST;
    $data["_id"] = $id;
    print_r($data);
    //db::log("Test1","LogUpload",["session"=>$_SESSION, "files_id"=>$id]);
} else {
    $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_SPECIAL_CHARS);
    if(!is_null($id) && $id !== FALSE) {
        #\Web\MongoUpload::download($bucket,$id);
        $bucket = db::mongo()->selectGridFSBucket([ "disableMD5"=> false, "bucketName"=>"upload" ]);
        $c = \Web\MongoUpload::get($bucket,$id,$ft);
        header("Content-Type: ".$ft);
        echo $c;
    } else {
        throw new Exception("Ther is no file or Id so nothing to do!");
    }
}

