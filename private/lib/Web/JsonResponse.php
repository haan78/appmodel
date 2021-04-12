<?php

namespace Web {

    use stdClass;
    use Exception;
    use ReflectionFunction;

    class JsonResponse
    {

        public const ERROR_FULL = 0;
        public const ERROR_MSG = 1;
        public const ERROR_NONE = 3;

        private static array $options = [
            "charset"=>"utf-8",
            "pretty" => false,
            "jsonbCallBack" =>null,
            "errorLevel" => 1
        ];

        public static function setOptions(array $options) {
            $keys = array_keys(static::$options);
            for($i=0; $i<count($keys); $i++) {
                $key = $keys[$i];
                if (array_key_exists($key,$options)) {
                    static::$options[$key] = $options[$key];
                }
            }
        }


        public static function perform(callable $method)
        {
            $response = null;
            try {                
                $response = new stdClass();
                $response->success = true;
                $params = self::generateParams($method);
                $response->data = call_user_func_array($method, $params);
            } catch (Exception $ex) {
                $response = self::error($ex);
            }
            self::push($response);
        }

        private static function generateParams(callable $method): array
        {
            $ref = new ReflectionFunction($method);
            $parameters = $ref->getParameters();
            $arr = [];
            for ($i = 0; $i < count($parameters); $i++) {
                $p = $parameters[$i];
                $name = $p->getName();
                if ($name == "post") {
                    array_push($arr, self::jsonPost());
                } elseif ($name == "pathinfo") {
                    array_push($arr, self::pathInfo());
                } elseif ($name == "session") {
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    array_push($arr, $_SESSION);
                } else {
                    array_push($arr, null);
                }
            }
            return $arr;
        }

        public static function pathInfo(): array
        {
            if (isset($_SERVER["PATH_INFO"])) {
                $pi = explode("/", $_SERVER["PATH_INFO"]);
                if (count($pi) >= 1 && $pi[0] == "") {
                    array_shift($pi);
                }
                return $pi;
            } else {
                return [];
            }
        }

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
                        throw new Exception("Post data cannot be parsed into Json / $jle");
                    }
                } else {
                    return null;
                }
            }
        }

        private static function push($response)
        {
            $charset = static::$options["charset"];
            if (headers_sent($filename, $linenum)) {
                $msg = "Headers already sent in $filename on line $linenum";
                throw new Exception($msg);
            }
            $json = json_encode($response, ( static::$options["pretty"] ? JSON_PRETTY_PRINT : 0));
            $cb = static::$options["jsonbCallBack"];
            if (!is_null($cb)) {
                header("Content-Type: application/javascript; charset=$charset");
                echo "$cb($json);";
            } else {
                header("Content-Type: application/json; charset=$charset");
                echo $json;
            }
        }
        
        private static function error(Exception $ex)
        {
            $r = new stdClass();
            $r->success = false;
            if (static::$options["errorLevel"] == static::ERROR_FULL) {
                $r->message = $ex->getMessage();
                $r->code = $ex->getCode();
                $r->file = $ex->getFile();
                $r->line = $ex->getLine();
            } elseif (static::$options["errorLevel"] == static::ERROR_MSG) {
                $r->message = $ex->getMessage();
                $r->code = $ex->getCode();
            }
            return $r;
        }
    }
}
