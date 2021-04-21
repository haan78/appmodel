<?php

namespace Web {
    function webpack(string $root, string $cssdir, string $jsdir, $name, &$head, &$body)
    {

        function startsWith($haystack, $needle)
        {
            $length = strlen($needle);
            return substr($haystack, 0, $length) === $needle;
        }

        $fulljsdir = $root . "/" . $jsdir;
        $fullcssdir = $root . "/" . $cssdir;
        $rnd = uniqid();
        $head = "";
        $body = "";

        if (file_exists($fullcssdir)) {
            $jslist = glob($fullcssdir . "/*.css");
            foreach ($jslist as $f) {
                $basename = basename($f) . "?$rnd";

                if (startsWith($basename, "chunk") || startsWith($basename, $name)) {
                    $head .= '<link href="' . $cssdir . '/' . $basename . '" rel="preload" as="style"/>';
                    $head .= '<link href="' . $cssdir . '/' . $basename . '" rel="stylesheet"/>';
                }
            }
        }
        if (file_exists($fulljsdir)) {
            $jslist = glob($fulljsdir . "/*.js");
            foreach ($jslist as $f) {
                $basename = basename($f) . "?$rnd";
                if (startsWith($basename, "chunk") || startsWith($basename, $name)) {
                    $head .= '<link href="' . $jsdir . '/' . $basename . '" rel="preload" as="script"/>';
                    $body .= '<script src="' . $jsdir . '/' . $basename . '"></script>';
                }
            }
        }
    }
}
