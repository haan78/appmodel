<?php

namespace Web {

    use Exception;
    use \MongoDB\BSON\ObjectId;
    use \MongoDB\GridFS\Bucket;


    class MongoUpload
    {
        public const UPLOAD_BEHAVIOR_ALL_FILES = "UPLOAD_BEHAVIOR_ALL_FILES";
        public const UPLOAD_BEHAVIOR_FIRST_FILE = "UPLOAD_BEHAVIOR_FIRST_FILE";

        private static $fileControlFnc = null;


        public static float $maxTotalUpSizeMb = 8; //8mb
        public static int $maxUploadCount = 10;
        public static bool $onlyImages = true;
        public static $type = "";
        //public static array $mimeTypes = ["image/png", "image/jpeg", "image/gif", "application/pdf", "image/svg+xml", "image/webp", "image/tiff"];

        public static function setFileControl(callable $fnc)
        {
            static::$fileControlFnc = $fnc;
        }

        private static function controlFiles(): void
        {

            if (empty($_FILES)) {
                throw new Exception("There is no upload file");
            } else {
                if ( count( array_keys($_FILES) ) > static::$maxUploadCount ) {
                    throw new Exception("Maximum upload limit is ".static::$maxUploadCount." files");
                }
                $total = 0;
                foreach ($_FILES as $f) {
                    $total += intval($f["size"]);
                    if (!is_null(static::$fileControlFnc)) {
                        call_user_func_array(static::$fileControlFnc, $f);
                    }
                }
                if ($total > (static::$maxTotalUpSizeMb * 1024 * 1024) ) {
                    throw new Exception("Maximum upload size is ".static::$maxTotalUpSizeMb."Mb");
                }                
            }
        }

        private static function toMongo(array $f, Bucket $bucket): string
        {
            $data = [
                "file_upload_local_time" => date("Y-m-d H:i:s"),
                "file_type" => $f["type"]
            ];
            $stream = fopen($f["tmp_name"], 'r');
            $_id = $bucket->uploadFromStream($f["name"], $stream, ["metadata" => $data]);
            return $_id->__toString();
        }

        public static function save(Bucket $bucket, string $behavior = self::UPLOAD_BEHAVIOR_FIRST_FILE): string
        {
            static::controlFiles();
            if ($behavior == self::UPLOAD_BEHAVIOR_ALL_FILES) {
                $list = [];
                foreach ($_FILES as $k => $f) {
                    array_push($list, self::toMongo($f, $bucket));
                }
                return implode(",", $list);
            } elseif ($behavior == self::UPLOAD_BEHAVIOR_FIRST_FILE) {
                return self::toMongo($_FILES[array_key_first($_FILES)], $bucket);
            } elseif (isset($_FILES[$behavior])) {
                return self::toMongo($_FILES[$behavior], $bucket);
            }
            throw new Exception("There is no file to be saved / $behavior");
        }

        public static function delete(\MongoDB\GridFS\Bucket $bucket, string $_id)
        {
            $id = new ObjectId($_id);
            $bucket->delete($id);
        }

        public static function download(\MongoDB\GridFS\Bucket $bucket, string $_id)
        {
            $id = new ObjectId($_id);

            $result = $bucket->findOne(["_id" => $id]);
            $destination = fopen('php://temp', 'w+b');
            $bucket->downloadToStream($id, $destination);
            header("Content-Type: " . $result->metadata->file_type);
            echo stream_get_contents($destination, -1, 0);
        }

        public static function get(\MongoDB\GridFS\Bucket $bucket, string $_id, &$file_type)
        {
            $id = new ObjectId($_id);
            $result = $bucket->findOne(["_id" => $id]);
            $destination = fopen('php://temp', 'w+b');
            $bucket->downloadToStream($id, $destination);
            $file_type = $result->metadata->file_type;
            return stream_get_contents($destination, -1, 0);
        }
    }
}
