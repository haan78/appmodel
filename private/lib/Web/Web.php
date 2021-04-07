<?php

namespace Web {

    

    use ErrorException;
    use stdClass;

    class Web
    {

        public static function errorHandler(?callable $fnc = null)
        {
            error_reporting(E_ALL);
            //ini_set('display_errors', TRUE);
            ini_set('display_errors', FALSE);
            ini_set('display_startup_errors', TRUE);
            register_shutdown_function(function() use($fnc) {
                $er = error_get_last();
                if (!is_null($er)) {
                    $ex = new ErrorException($er["message"], $er["type"], 0, $er["file"], $er["line"]);
                    if ( !is_null($fnc) ) {
                        call_user_func_array( $fnc,[$ex] );
                    } else {
                        print_r($er);
                    }                    
                }
            });
            set_error_handler(function ($errno, $errstr, $errfile, $errline) use($fnc) {
                $ex = new ErrorException($errstr, $errno, 0, $errfile, $errline);
                if ( is_null($fnc) ) {
                    throw $ex;
                } else {
                    call_user_func_array($fnc,[$ex]);
                }
            }, E_ALL);
        }

        public static function action() {
            $action = "";
            if ( isset($_SERVER["PATH_INFO"]) && !empty($_SERVER["PATH_INFO"]) ) {
                $arr = explode("/",$_SERVER["PATH_INFO"]);
                if ( isset($arr[1]) ) {
                    $action = $arr[1];
                } elseif($arr[0]) {
                    $action = trim($arr[0]);
                }
            }
            return $action;
        }
    }
}
