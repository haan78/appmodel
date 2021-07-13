<?php

namespace Web {

use Exception;

class Json {
        public static int $JSON_FLAGS = 0;

        public static $responseformat;
        public static $errorFormat;

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

            $success = true;
            $data = null;
            if ( $result instanceof Exception ) {
                $success = false;
                if ( is_callable(static::$errorFormat)  ) {
                    $data = \call_user_func_array( static::$errorFormat,[ $result ] );
                } else {
                    $data = static::errorData($result);
                }
            }

            if ( is_callable(static::$responseformat) ) {
                $data = \call_user_func_array(static::$responseformat,[$success,$result]);
            } else {
                $data = [
                    "success" => $success,
                    "data" => $result
                ];
            }

            if ( !headers_sent() ) {
                header("Content-Type: application/json; charset=utf-8");
            }
            echo json_encode($data,static::$JSON_FLAGS);
        }

        public static function errorData(Exception $ex) : array {
            return [
                "message" => $ex->getMessage(),
                "code" => $ex->getCode(),
                "line" => $ex->getLine(),
                "file" => $ex->getFile(),
                "class" => get_class($ex)
            ];           
        }

        public static function perform(callable $fnc) : void {
            Web::errorHandler(function (Exception $ex) {
                Json::output( $ex );
            });
            try {
                static::output(\call_user_func_array($fnc,[static::jsonPost()]));
            } catch (Exception $ex) {
                static::output( $ex );
            }            
        }
    }
}