<?php

namespace Web {

    class Ticket {
        public static string $HEADER_NAME = 'HTTP_TICKET';
        public static string $SESSION_NAME = "TICKET_HASH";
        public static string $COOKIE_NAME = "TICKET";
        public static int $TIME = 60;
        
        public static function pass(Session $session) : bool {
            if ( isset($_SERVER[static::$HEADER_NAME]) ) {
                return ( $_SERVER[static::$HEADER_NAME] . $_SERVER["HTTP_USER_AGENT"] == $session->get(static::$SESSION_NAME) );
            } else {
                return false;
            }
        }
        public static function save(Session $session) : void {
            $ticket = uniqid(date("YmdHis"),true);
            $session->set(static::$SESSION_NAME,$ticket . $_SERVER["HTTP_USER_AGENT"]);
            setcookie(static::$COOKIE_NAME, $ticket, time() + static::$TIME);
        }                
        
    }
    
}