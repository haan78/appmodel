<?php

namespace Web {

    class Ticket {
        public static string $HEADER_NAME = 'HTTP_TICKET';
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
                return ( $_SERVER[static::$HEADER_NAME] ==  $this->session->get(static::$HEADER_NAME) );
            }
            return false;
        }
        public function save() : string {
            $t = hash( "sha256", date("YmdHis") . uniqid() . rand(1,177) );
            $this->session->clear();
            $this->session->set(static::$HEADER_NAME,$t);
            return $t;
        }

        public static function generateMetaWithTicket() : \stdClass {
            $t = new Ticket( new SessionDefault() );
            $md = new \stdClass();
            $md->__TICTKE__ = $t->save();
            return $md;
        }

                
        
    }
    
}