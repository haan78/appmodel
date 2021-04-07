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
        public static function assert(string $message = "Auth Faild!")
        {
            $md = new stdClass();
            if (!static::get($md)) {
                throw new Exception($message);
            }
        }
    }
}
