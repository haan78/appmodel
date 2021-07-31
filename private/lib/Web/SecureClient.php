<?php
namespace Web {

use Exception;

class SecureClient {
        public static int $checkFailStatus = 0;
        public static string $SESSION_NAME = "CLIENT_ID";
        
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
    
        private static function getId() {
            return md5(static::client().$_SERVER["HTTP_USER_AGENT"]);
        }

        public static function save(Session $session) : void {
            $session->set(static::$SESSION_NAME,static::getId());
        }

        public static function check(Session $session) : bool {
            $val = $session->get(static::$SESSION_NAME,FALSE);
            if ($val !==FALSE) {
                if ( static::getId() == $val ) {
                    static::$checkFailStatus = 0;
                } else {
                    static::$checkFailStatus = 2;
                }
            } else {
                static::$checkFailStatus = 1;
            }
            return static::$checkFailStatus == 0;
        }

        public static function validate(Session $session, string $message = "Client cannot validate") : void {
            if ( !static::check($session) ) {
                throw new Exception($message." / ".static::$checkFailStatus);
            }
        }
    }
}