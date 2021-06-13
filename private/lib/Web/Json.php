<?php

namespace Web {

use Exception;

class Json {
        public static int $JSON_FLAGS = 0;
        public static int $ERROR_LEVEL = 1;
        public static function jsonPost()
        {
            if (!empty($_POST)) {
                return (object)$_POST;
            } else {
                $PD = file_get_contents("php://input");
                if (!empty($PD)) { //Json has been sent
                    $jd = json_decode($PD);
                    $jle = json_last_error();
                    if ($jle == JSON_ERROR_NONE) {
                        return $jd;
                    } else {
                        throw new \Exception("Post data cannot be parsed into Json / $jle");
                    }
                } else {
                    return null;
                }
            }
        }

        public static function output($result) {
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($result,static::$JSON_FLAGS);
        }

        public static function error(Exception $ex) : void {
            $arr = [
                "message" => $ex->getMessage()
            ];
            if ( static::$ERROR_LEVEL == 1 ) {
                $arr["code"] = $ex->getCode();
                $arr["line"] = $ex->getLine();
                $arr["file"] = $ex->getFile();
            }
            
            static::output([
                "success" => false,
                "data" => $arr
            ]);
        }

        public static function perform(callable $fnc) : void {
            Web::errorHandler(function (Exception $ex) {
                Json::error($ex);
            });
            try {
                static::output([
                    "success" => true,
                    "data" => \call_user_func_array($fnc,[static::jsonPost()])
                ]);
            } catch (Exception $ex) {
                static::error($ex);
            }            
        }
    }
}