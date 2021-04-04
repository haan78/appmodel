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

        public static final function test($success, $reject, $reload): void
        { //0 = pass, 1 = reload, 2 = reject
            $md = new stdClass();
            $js = $reject;
            if (static::get($md)) {
                $js = $success;
            } elseif (static::set($md)) {
                header("Refresh:0; url=/$reload");
                return;
            }
            if (!file_exists($js)) {
                throw new Exception("File not found $js");
            }
            $metaStr = static::buildMetaStr($md);
            $jsContent = file_get_contents($js);

            ob_start();
?>
            <!DOCTYPE html>
            <html lang="tr">

            <head profile="http://www.w3.org/2005/10/profile">
                <meta charset='utf-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <meta name="backend" content="<?php echo "$metaStr"; ?>">
                <link rel="icon" href="favicon.ico">
                <title></title>
            </head>

            <body>
                <div id="app"></div>
                <script>
                    <?php echo $jsContent; ?>
                </script>
            </body>

            </html>
<?php ob_end_flush();
        }

        public static final function buildMetaStr(stdClass $metadata): string
        {
            $key = static::keyBuild();
            $md = clone $metadata;
            $md->exp = time() + static::$expire;
            $token = \Firebase\JWT\JWT::encode($md, $key);
            return $token . "|" . $key;
        }

        protected static function keyBuild(): string
        {
            return hash("sha256", date("YmdHis") . (string)openssl_random_pseudo_bytes(40) . uniqid());
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
