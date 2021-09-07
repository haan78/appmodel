<?php
namespace Web {
    class Template {
        public static int $time = 120;
        public static bool $useRnd = true;
        public static string $COOKIE_NAME = "SUBUTAI";

        private array $scripts;
        private string $file;

        public function __construct(array $scripts,string $file) {
            $this->scripts = $scripts;
            $this->file = $file;
        }

        public function prepare(string $content) : string {
            $scripts = $this->scripts;
            $rnd = "";
            if (static::$useRnd) {
                $rnd = "?".uniqid();    
            }
            $html_js = "";
            $html_css = "";
            for($i=0; $i<count($scripts); $i++) {
                $s = $scripts[$i];
                $arr = explode(".",$s);
                $ext = ( count($arr)>1 ? strtolower( end($arr) ) : "" );
                if ( $ext == "js" ) {
                    $html_js .= "<script src=\"$s$rnd\"></script>";
                } elseif ( $ext == "css" ) {
                    $html_css .= "<link rel=\"stylesheet\" href=\"$s$rnd\" />";
                }
            }
            return str_replace(["<!--CSS-->","<!--JS-->"],[$html_css, $html_js],$content);
        }
    
        private function data(array $data) {
            setcookie(self::$COOKIE_NAME, json_encode($data), time()+static::$time);            
        }

        public static function html(array $scripts, string $file, array $data = []) : void {
            $t = new Template($scripts,$file);
            $t->loadHtml($data);
        }
    
        public function loadHtml(array $data = []) {
            $this->data($data);
            if ( file_exists($this->file) ) {
                header("Content-Type: text/html; charset=utf-8");
                echo $this->prepare(file_get_contents($this->file));
            } else {
                throw new \Exception("File not found / ".$this->file);
            }
        }
    
        public function below(array $data = []) {
            $this->data($data);
            $self = $this;
            ob_start(function ($html) use($self) {
                return $self->prepare($html);
            });
        }
    }
}