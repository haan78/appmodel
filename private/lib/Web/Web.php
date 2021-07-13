<?php

namespace Web {

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

        private static $path_info = false;
        public static function pathInfo(bool $reload = false): array
        {
            if ( self::$path_info !== false && $reload === false ) {
                return self::$path_info;
            }
            if (isset($_SERVER["PATH_INFO"])) {
                $pi = explode("/",trim( $_SERVER["PATH_INFO"] ) );
                if (count($pi) >= 1 && $pi[0] == "") {
                    array_shift($pi);
                }
                self::$path_info = $pi;
            } else {
                self::$path_info = [];
            }
            return self::$path_info;
        }

        public static function client() : string {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                return trim(explode(",",$_SERVER["HTTP_X_REAL_IP"])[0]);
            } elseif ( isset($_SERVER["HTTP_X_REAL_IP"]) ) {
                return trim($_SERVER["HTTP_X_REAL_IP"]);
            } elseif ( isset($_SERVER["REMOTE_ADDR"]) ) {
                return trim($_SERVER["REMOTE_ADDR"]);
            } else {
                return "";
            }
        }

        public static function path(int $index = 0,string $default = "") : string {
            return isset(self::pathinfo()[$index]) && !empty(self::pathinfo()[$index]) ? self::pathinfo()[$index] : $default;
        }

        public static function errorHandler(callable $fnc, bool $stopOnError = true )
        {
            error_reporting(E_ALL);
            //ini_set('display_errors', TRUE);
            ini_set('display_errors', FALSE);
            ini_set('display_startup_errors', TRUE);
            register_shutdown_function(function() use($fnc) {
                $er = error_get_last();
                if (!is_null($er) && $er["type"] == E_ERROR) {
                    $ex = new WebErrorException($er["message"], $er["type"], 0, $er["file"], $er["line"]);
                    http_response_code(200);
                    call_user_func_array( $fnc,[$ex] );

                }
            });
            set_error_handler(function ($errno, $errstr, $errfile, $errline) use($fnc,$stopOnError) {
                $ex = new WebErrorException($errstr, $errno, 0, $errfile, $errline);
                call_user_func_array($fnc,[$ex]);
                if ( $stopOnError ) {
                    exit();
                }
            }, E_ALL);
        }
    }
}
