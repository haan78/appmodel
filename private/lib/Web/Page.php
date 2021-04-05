<?php

namespace Web {

    use stdClass;

    abstract class Page
    {
        public static string $cssDir = "css";
        public static string $jsDir = "js";
        public static int $expire = 40;

        private static final function startsWith( $haystack, $needle ) {
            $length = strlen( $needle );
            return substr( $haystack, 0, $length ) === $needle;
        }

        public static final function load($root, $name, stdClass $md): void
        {
            $jsdir = $root . "/" . static::$jsDir;
            $cssdir = $root . "/" . static::$cssDir;
            $preload = "";
            $stylesheet = "";
            $script = "";
            $rnd = uniqid();
            if (file_exists($cssdir)) {
                $jslist = glob($cssdir . "/*.css");
                foreach ($jslist as $f) {
                    $basename = basename($f)."?$rnd";

                    if (static::startsWith($basename, "chunk") || static::startsWith($basename, $name)) {
                        $preload .= '<link href="css/' . $basename . '" rel="preload" as="style"/>' . PHP_EOL;
                        $stylesheet .= '<link href="css/' . $basename . '" rel="stylesheet">' . PHP_EOL;
                    }
                }
            }
            if (file_exists($jsdir)) {
                $jslist = glob($jsdir . "/*.js");
                foreach ($jslist as $f) {
                    $basename = basename($f)."?$rnd";
                    if (static::startsWith($basename, "chunk") || static::startsWith($basename, $name)) {
                        $preload .= '<link href="/js/' . $basename . '" rel="preload" as="script"/>' . PHP_EOL;
                        $script .= '<script src="/js/' . $basename . '"></script>' . PHP_EOL;
                    }
                }
            }
            $metadata = '<meta name="backend" content="'.static::buildMetaStr($md).'">';
            ob_start();
            static::template($preload,$stylesheet,$metadata,$script);
            ob_end_flush();
        }

        protected static abstract function template($preload,$stylesheet,$metadata,$script):void;

        private static final function buildMetaStr(stdClass $metadata): string
        {
            $key = hash("sha256", date("YmdHis") . (string)openssl_random_pseudo_bytes(40) . uniqid());
            $md = clone $metadata;
            $md->exp = time() + static::$expire;
            $token = \Firebase\JWT\JWT::encode($md, $key);
            return $token . "|" . $key;
        }
    }
}
