<?php

namespace Web {

    class Ticket {
        public static string $HEADER_NAME = 'HTTP_TICKET';
        public static string $SESSION_NAME = "TICKET_HASH";
        private Session $session;
        public function __construct(Session $session) {
            $this->session = $session;
        }

        public function getSession() : Session {
            return $this->session;
        }

        public function exist() : bool {
            return isset($_SERVER[static::$HEADER_NAME]);
        }
        public function pass() : bool {
            if ( isset($_SERVER[static::$HEADER_NAME]) ) {
                $hash = hash( "sha256", $_SERVER[static::$HEADER_NAME] . $_SERVER["HTTP_USER_AGENT"]);
                return ( $hash == $this->session->get(static::$SESSION_NAME) );
            }
            return false;
        }
        public function save() : string {
            $ticket = uniqid(date("YmdHis"),true);
            $hash = hash( "sha256", $ticket . $_SERVER["HTTP_USER_AGENT"]);
            $this->session->clear();
            $this->session->set(static::$SESSION_NAME,$hash);
            return $ticket;
        }
                
        
    }
    
}