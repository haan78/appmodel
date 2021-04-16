<?php

namespace Web {

    use stdClass;

    class Vue
    {
        public static string $cssDir = "css";
        public static string $jsDir = "js";
        public static int $expire = 40;

        private static final function startsWith( $haystack, $needle ) {
            $length = strlen( $needle );
            return substr( $haystack, 0, $length ) === $needle;
        }

        public static final function scriptList($root, $name, stdClass $md): array
        {
            $jsdir = $root . "/" . static::$jsDir;
            $cssdir = $root . "/" . static::$cssDir;
            $rnd = uniqid();
            $list = [];
            $str = static::buildMetaStr($md);
            array_push($list,[ "stage"=>"beckend", "type"=>"meta", "script" => null, "code"=>'<meta name="backend" content="'.$str.'">'  ]);
            
            if (file_exists($cssdir)) {
                $jslist = glob($cssdir . "/*.css");
                foreach ($jslist as $f) {
                    $basename = basename($f)."?$rnd";

                    if (static::startsWith($basename, "chunk") || static::startsWith($basename, $name)) {
                        array_push($list,["stage"=>"preload", "type"=>"css","script"=>"css/$basename", "code"=> '<link href="css/' . $basename . '" rel="preload" as="style"/>']);
                        array_push($list,["stage"=>"stylesheet","type"=>"css","script"=>"css/$basename", "code" => '<link href="css/' . $basename . '" rel="stylesheet"/>']);
                    }
                }
            }
            if (file_exists($jsdir)) {
                $jslist = glob($jsdir . "/*.js");
                foreach ($jslist as $f) {
                    $basename = basename($f)."?$rnd";
                    if (static::startsWith($basename, "chunk") || static::startsWith($basename, $name)) {
                        array_push($list,["stage"=>"preload", "type"=>"js","script"=>"css/$basename", "code"=> '<link href="/js/' . $basename . '" rel="preload" as="script"/>' ]);
                        array_push($list,["stage"=>"body","type"=>"js","script"=>"css/$basename", "code"=> '<script src="/js/' . $basename . '"></script>']);
                    }
                }
            }

            return $list;
        }

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
