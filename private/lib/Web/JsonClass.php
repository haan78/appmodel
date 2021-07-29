<?php

namespace Web {

    use Exception;
    use ReflectionMethod;
    use Throwable;
    use Error;

class JsonClass {

        public static string $jsonpName = "jsonp";
        public static int $JSON_FLAGS = 0;


        public static function post() {
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
        public static function pathInfo(int $ind) {
            if (isset($_SERVER["PATH_INFO"])) {
                $pi = explode("/", trim($_SERVER["PATH_INFO"]));
                if (count($pi) >= 1 && $pi[0] == "") {
                    array_shift($pi);
                }
                if (isset($pi[$ind])) {
                    return trim($pi[$ind]);
                } elseif ($ind = -1) {
                    return count($pi);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public static function client(): string {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                return trim(explode(",", $_SERVER["HTTP_X_REAL_IP"])[0]);
            } elseif (isset($_SERVER["HTTP_X_REAL_IP"])) {
                return trim($_SERVER["HTTP_X_REAL_IP"]);
            } elseif (isset($_SERVER["REMOTE_ADDR"])) {
                return trim($_SERVER["REMOTE_ADDR"]);
            } else {
                return "";
            }
        }

        private function getMethodName($definer) : string {
            if ( is_int($definer) ) {
                $res = static::pathInfo($definer);
                if ( is_string($res) && !empty($res) ) {
                    return trim($res);
                } else {
                    throw new Exception("Path info number $definer doesn't  exist");
                }
            } elseif ( is_string($definer) ) {
                $res = filter_input(INPUT_GET,  $definer, FILTER_SANITIZE_STRING);
                if (is_string($res) && !empty($res) ) {
                    return trim($res);
                } else {
                    throw new Exception("URL query name $definer doesn't exist");
                }
            } else {
                throw new Exception("Method definer must be path info number or URL query name");
            }
        }

        public function __construct($methodDefiner) {
            $result = null;
            try {
                $methodName = $this->getMethodName($methodDefiner);
                if (method_exists($this, $methodName)) {
                    $rfm = new ReflectionMethod($this, $methodName);
                    if (($rfm->isPublic()) && (!$rfm->isConstructor()) && (!$rfm->isDestructor()) && (!$rfm->isStatic())) {
                        $result = $this->doSuccess($rfm->invokeArgs($this, array(static::post())));
                    } else {
                        throw new Exception("Method is not callable");
                    }
                } else {
                    throw new Exception("Method $methodName dose not exist");
                } 
            } catch (Error $err) {
                $result = $this->doError($err);
            } catch (Exception $ex) {
                $result = $this->doError($ex);
            }
            $this->doResponse($result);
        }

        protected function doResponse($json) {
            $p = filter_input(INPUT_GET,  self::$jsonpName, FILTER_SANITIZE_STRING);
            if (($p != null) && ($p != false)) {
                header('Content-Type: application/javascript; charset=utf-8');
                echo "if (typeof " . $p . " === 'function' ) $p( " . json_encode($json, true) . " ); else alert('Function " . $p . " not found.'); ";
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($json, static::$JSON_FLAGS);
            }
        }

        protected function doSuccess($data) {
            return array("success" => true, "data" => $data);
        }

        protected function doError(Throwable $ex) {
            return array("success" => false, "data" => [
                "message" => $ex->getMessage(),
                "code" => $ex->getCode(),
                "line" => $ex->getLine(),
                "file" => $ex->getFile(),
                "class" => get_class($ex)
            ]);
        }
    }
}
