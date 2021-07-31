<?php

namespace Web {

    use Exception;
    use ReflectionMethod;
    use Throwable;
    use Error;

class JsonClassException extends Exception {
}

class JsonClass {

        public static string $JSONP = "jsonp";
        public static int $JSON_FLAGS = 0;
        public static bool $SHOW_ERROR_DETAILS = true;


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
                        throw new JsonClassException("Post data cannot be parsed into Json / $jle",201);
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

        private function getMethodName($definer) : string {
            if ( is_int($definer) ) {
                $res = static::pathInfo($definer);
                if ( is_string($res) && !empty($res) ) {
                    return trim($res);
                } else {
                    throw new JsonClassException("Path info number $definer doesn't  exist",102);
                }
            } elseif ( is_string($definer) ) {
                $res = filter_input(INPUT_GET,  $definer, FILTER_SANITIZE_STRING);
                if (is_string($res) && !empty($res) ) {
                    return trim($res);
                } else {
                    throw new JsonClassException("URL query name $definer doesn't exist",103);
                }
            } else {
                throw new JsonClassException("Method definer must be path info number or URL query name",101);
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
                        throw new JsonClassException("Method is not callable",302);
                    }
                } else {
                    throw new JsonClassException("Method $methodName dose not exist",301);
                } 
            } catch (Error $err) {
                $result = $this->doError($err);
            } catch (Exception $ex) {
                $result = $this->doError($ex);
            }
            $this->doResponse($result);
        }

        protected function doResponse($json) {
            $p = filter_input(INPUT_GET,  self::$JSONP, FILTER_SANITIZE_STRING);
            if (($p != null) && ($p != false)) {
                header('Content-Type: application/javascript; charset=utf-8');
                echo "if (typeof $p === 'function' ) $p( " . json_encode($json, static::$JSON_FLAGS) . " ); else consloe.error('Function $p not found.'); ";
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($json, static::$JSON_FLAGS);
            }
        }

        protected function doSuccess($data) {
            return array("success" => true, "data" => $data);
        }

        protected function doError(Throwable $ex) {
            $arr = array("success" => false, "data" => [
                "message" => $ex->getMessage(),
                "code" => $ex->getCode(),
                "class" => get_class($ex)
            ]);
            if ( !$ex instanceof JsonClassException && static::$SHOW_ERROR_DETAILS) {
                $arr["data"]["file"] = $ex->getFile();
                $arr["data"]["line"] = $ex->getLine();
            } 
            return $arr;
        }
    }
}
