<?php
namespace Web {
    class Template {
        public static int $time = 120;
        public static bool $useRnd = true;
    
        private static function data(array $data) {
            foreach( $data as $k => $v ) {
                setcookie($k, $v, time()+static::$time);
            }
        }
    
        public static function __prepare(array $cssList, array $jsList, string $content) {        
            $rnd = "";
            if (static::$useRnd) {
                $rnd = uniqid();    
            }
            $js = "";
            for($i=0; $i<count($jsList); $i++) {
                $f = $jsList[$i];
                $js.="<script src=\"$f?$rnd\"></script>";
            }
            $css = "";
            for ($i=0; $i<count($cssList); $i++) {
                $f = $cssList[$i];
                $css.="<link rel=\"stylesheet\" href=\"$f?$rnd\" />";
            }
            return str_replace(["<!--CSS-->","<!--JS-->"],[$css,$js],$content);
        }
    
        public static function load(array $cssList, array $jsList, string $file, array $data = []) {
            static::data($data);
            if ( file_exists($file) ) {
                echo static::__prepare($cssList,$jsList,file_get_contents($file));
            } else {
                throw new \Exception("File not found / $file");
            }
        }
    
        public static function below(array $cssList, array $jsList, array $data = []) {
            static::data($data);
            ob_start(function ($html) use($cssList, $jsList) {
                return Template::__prepare($cssList,$jsList,$html);
            });
        }
    }
}