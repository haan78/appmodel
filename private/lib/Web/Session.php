<?php

namespace Web {
    interface Session
    {
        public function set(string $name, $value) : void;
        public function get(string $name,$default = false);
        public function stack() : array;
        public function clear() : void;
        public function kill() : void;
        public function start() : void;
        public function started() : bool;
        public function close() : void;
    }

    class SessionDefault implements Session {
        public function set(string $name, $value) : void {
            $this->start();
            $_SESSION[$name] = $value;
        }

        public function get(string $name, $default = false) {
            $this->start();
            return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
        }

        public function stack() : array {
            $data = [];
            $this->start();
            foreach($_SESSION as $key => $val) {
                $data[$key] = $val;
            }
            return $data;
        }

        public function kill() : void {
            $this->clear();
            session_destroy();            
            session_write_close();
        }

        public function clear(): void
        {
            $this->start();
            session_unset();
            $ckeys = array_keys($_COOKIE);
            for ($i=0; $i< count($ckeys); $i++) {
                $ck = $ckeys[$i];
                setcookie($ck, "", time() - 3600);
            }
        }
        
        public function start() : void {
            if (!$this->started()) {
                if (!headers_sent($hf, $hl)) {
                    session_start();
                } else {
                    throw new \Exception("Header has been sent before $hf / $hl");
                }
            }
        }
        public function started() : bool {
            return isset($_SESSION);
        }

        public function close() : void {
            if ( $this->started() ) {
                session_write_close();
            }
        }
    }
}