<?php

namespace Web {
    class PathInfo {
        private static $arr = false;
        
        private static function parse() : array {
            if (isset($_SERVER["PATH_INFO"])) {
                $pi = explode("/",trim( $_SERVER["PATH_INFO"] ) );
                if (count($pi) >= 1 && $pi[0] == "") {
                    array_shift($pi);
                }
                return $pi;
            } else {
                return [];
            }
        }
        public static function count() : int {
            if (self::$arr === false) {
                self::$arr = self::parse();
            }
            return count(self::$arr);
        }

        public static function item(int $index) {
            if (self::$arr === false) {
                self::$arr = self::parse();
            }
            if ( isset(self::$arr[$index]) ) {
                return trim(self::$arr[$index]);
            } else {
                return false;
            }
        }
    }
}