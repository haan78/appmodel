<?php

namespace Web {

    use stdClass;

    abstract class AuthUser
    {

        public const TEST_ACCEPT = 0;
        public const TEST_REJECT = 1;
        public const TEST_RELOAD = 2;

        public static final function testForLogin(?stdClass &$md): int
        { //0 = accept, 1 = reload, 2 = reject
            $md = new stdClass();
            $md->__TICKET__ = Web::saveTicket();
            if (static::get($md)) {
                return static::TEST_ACCEPT;
            } elseif (static::set($md)) {
                return static::TEST_RELOAD;
            } else {
                return static::TEST_REJECT;
            }
        }

        public static function testForService(stdClass &$md, string $action) : bool {
            if ( !static::set($md,$action) ) {
                return false;
            } else {
                return true;
            }
        }

        protected abstract static function set(stdClass &$metaData): bool;
        protected abstract static function get(stdClass &$metaData): bool;
    }
}
