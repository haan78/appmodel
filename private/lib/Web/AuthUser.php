<?php

namespace Web {

    use stdClass;
    use Exception;

    abstract class AuthUser
    {
        protected static string $userField = "user";
        protected static string $passField = "pass";
        protected static string $captchaField = "captcha";
        protected static string $statusField = "status";
        protected static string $tokenField = "token";
        protected static int $expire = 40;

        public const TEST_ACCEPT = 0;
        public const TEST_REJECT = 1;
        public const TEST_RELOAD = 2;

        public static final function test(?stdClass &$md): int
        { //0 = accept, 1 = reload, 2 = reject
            $md = new stdClass();
            $md->__TICKET__ = static::sevaTicket();
            if (static::get($md)) {
                return static::TEST_ACCEPT;
            } elseif (static::set($md)) {
                return static::TEST_RELOAD;
            } else {
                return static::TEST_REJECT;
            }
        }

        private static function sessionStart()
        {
            if (!isset($_SESSION)) {
                if (!headers_sent($hf, $hl)) {
                    session_start();
                } else {
                    throw new Exception("Header has been sent before $hf / $hl");
                }
            }
        }

        private static function sevaTicket() : string {
            $t = hash( "sha256", date("YmdHis") . uniqid() . rand(1,177) );
            static::sessionStart();
            $_SESSION["HTTP_TICKET"] = $t;
            return $t;
        }

        public static function testTicket() : bool {
            if ( isset($_SERVER["HTTP_TICKET"]) ) {
                return ( $_SERVER["HTTP_TICKET"] ==  static::sessionGet("HTTP_TICKET"));
            }
            return false;
        }

        public static function sessionGet(string $name, $default = null)
        {
            static::sessionStart();
            return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
        }
        public static function sessionSet(string $name, $value): void
        {
            static::sessionStart();
            $_SESSION[$name] = $value;
        }

        public static function sessionKill(): void
        {
            static::sessionStart();
            session_unset();
        }

        public static function sessionClose()
        {
            if (isset($_SESSION)) {
                session_write_close();
            }
        }
        protected abstract static function set(stdClass &$metaData): bool;
        protected abstract static function get(stdClass &$metaData): bool;
        public static function assert(string $message = "You shall not pass!")
        {
            $md = new stdClass();
            if ( !static::testTicket() || !static::get($md) ) {
                throw new Exception($message);
            }
        }
    }
}
