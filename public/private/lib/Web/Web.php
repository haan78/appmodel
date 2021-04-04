<?php

namespace Web {

    const ERROR_FULL = 0;
    const ERROR_MSG = 1;
    const ERROR_NONE = 3;

    use ErrorException;
    use Exception;
    use stdClass;

    class Web
    {

        private static function setErrorHandler(?callable $fnc = null)
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

        public static function init (callable $router, ?callable $handler = null) {
            self::setErrorHandler($handler);
            $ifiles = get_included_files();
            if ( $ifiles[0] == "/app/index.php" ) {
                if ( isset($_SERVER["PATH_INFO"]) && !empty($_SERVER["PATH_INFO"]) ) {
                    $arr = explode("/",$_SERVER["PATH_INFO"]);
                    if ( isset($arr[1]) ) {
                        $path = $arr[1];
                        call_user_func_array($router,[$path]);
                    } else {
                        throw new Exception("Nginix Path Problem 2");
                    }
                } else {
                    throw new Exception("Nginix Path Problem 1");
                }
            } else {
                throw new Exception("The only index file that can run");
            }
        }

        public static function jsonResponse(callable $method,?stdClass $options = null) : void
        {
            require_once __DIR__ . '/JsonResponse.php';
            JsonResponse::perform($method,$options);
        }

        public static function upload(string $folder, ?stdClass $options = null) : void
        {
            require_once __DIR__ . '/Upload.php';
            Upload::perform($folder,$options);
        }
    }
}
