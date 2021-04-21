<?php

namespace Web {

use Exception;

define("HTTP_TICKET","HTTP_TICKET");

    class WebErrorException extends \ErrorException
    {
        public function details(): array
        {
            return [
                "message"  => $this->getMessage(),
                "code" => $this->getCode(),
                "file" => $this->getFile(),
                "line" => $this->getLine()
            ];
        }
    }

    interface Page {
        public static function scritp(string $head, string $body) : void;
        public static function json( $data ) : void;
    }


    class Web
    {

        private static $json_data = false;
        private static $path_info = false;
        public static function pathInfo(bool $reload = false): array
        {
            if ( self::$path_info !== false && $reload === false ) {
                return self::$path_info;
            }
            if (isset($_SERVER["PATH_INFO"])) {
                $pi = explode("/", $_SERVER["PATH_INFO"]);
                if (count($pi) >= 1 && $pi[0] == "") {
                    array_shift($pi);
                }
                self::$path_info = $pi;
            } else {
                self::$path_info = [];
            }
            return self::$path_info;
        }

        public static function path(int $index = 0) : string {
            return isset(self::pathinfo()[$index]) ? self::pathinfo()[$index] : "";
        }

        public static function errorHandler(callable $fnc = null)
        {
            error_reporting(E_ALL);
            //ini_set('display_errors', TRUE);
            ini_set('display_errors', FALSE);
            ini_set('display_startup_errors', TRUE);
            register_shutdown_function(function() use($fnc) {
                $er = error_get_last();
                if (!is_null($er)) {
                    $ex = new WebErrorException($er["message"], $er["type"], 0, $er["file"], $er["line"]);
                    call_user_func_array( $fnc,[$ex] );                   
                }
            });
            set_error_handler(function ($errno, $errstr, $errfile, $errline) use($fnc) {
                $ex = new WebErrorException($errstr, $errno, 0, $errfile, $errline);
                call_user_func_array($fnc,[$ex]);
            }, E_ALL);
        }

        public static function jsonPost(bool $reload = false)
        {
            if ( self::$json_data !== false && $reload === false ) {
                return self::$json_data;
            }
            if (!empty($_POST)) {
                self::$json_data = (object)$_POST;
            } else {
                $PD = file_get_contents("php://input");
                if (!empty($PD)) { //Json has been sent
                    $jd = json_decode($PD);
                    $jle = json_last_error();
                    if ($jle == JSON_ERROR_NONE) {
                        self::$json_data = $jd;
                    } else {
                        throw new \Exception("Post data cannot be parsed into Json / $jle");
                    }
                } else {
                    self::$json_data = null;
                }
                return self::$json_data;
            }
        }

        public static function exec(callable $method)
        {

            $params = self::generateParams($method);
                try {
                    return [
                        "success" => true,
                        "data"=> call_user_func_array($method, $params)
                    ];
                } catch (Exception $ex) {
                    return [
                        "success" => false,
                        "data"=>[
                            "message"  => $ex->getMessage(),
                            "code" => $ex->getCode(),
                            "file" => $ex->getFile(),
                            "line" => $ex->getLine()
                        ]
                    ];
                }            
        }

        private static function generateParams(callable $method): array
        {
            $ref = new \ReflectionFunction($method);
            $parameters = $ref->getParameters();
            $arr = [];
            for ($i = 0; $i < count($parameters); $i++) {
                $p = $parameters[$i];
                $name = $p->getName();
                if ($name == "post") {
                    array_push($arr, self::jsonPost());
                } elseif ($name == "pathinfo") {
                    array_push($arr, self::pathInfo());
                } elseif ($name=="req") {
                    $req = array_merge($_GET,self::jsonPost());
                    array_push($arr, $req);
                } else {
                    array_push($arr, null);
                }
            }
            return $arr;
        }
    }
}
