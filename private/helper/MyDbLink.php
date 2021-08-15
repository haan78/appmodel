<?php


class MyDbLink {

    public static array $options = [
        "timeout"=>20,
        "host"=>"localhost",
        "user"=>"root",
        "password"=>null,
        "database"=>null,
        "port"=>false,
        "charset"=>"utf8",
        "ssl_use"=> false,
        "ssl_key" => null,
        "ssl_certificate" => null,
        "ssl_ca_certificate" => null,
        "ssl_ca_path"=>null,
        "ssl_cipher" => null
    ];

    

    public static function link(?array $options = null) {

        $op = self::$options;
        if ( !is_null($options) ) {
            $keys = array_keys($op);
            for($i=0; $i<count($keys); $i++ ) {
                $key = $keys[$i];
                if ( array_key_exists($key,$options) ) {
                    $op[$key] = $options[$key];
                }
            }
        }
        $o = (object)$op;

        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT,$o->timeout);
        if ( $o->ssl_use ) {
            mysqli_options($link, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT,true);
            mysqli_ssl_set($link,$o->ssl_key,$o->ssl_certificate,$o->ssl_ca_certificate,$o->ssl_ca_path,$o->ssl_cipher);
        }
        mysqli_real_connect($link,$o->host,$o->user,$o->password,$o->database,$o->port);        
        mysqli_set_charset($link, $o->charset);
        return $link;
    }

    public static function test(?array $options) {
        $link = self::link($options);
        $result = mysqli_query($link,"SELECT VERSION()");
        if($result){
            if ($row = mysqli_fetch_array($result)) {
                echo "TEST OK => ".$row[0];
            }
        } else {
            echo "TEST FAIL => ".mysqli_error($link);
        }
        mysqli_close($link);

    }
}


MyDbLink::test([
    "ssl_use" => true,
    "user" => "admin",
    "host" => "maria1-210813-yeni.c0jurivlhyxf.eu-central-1.rds.amazonaws.com",
    "password" => "waGSfLf2SMejpu9b",
    "ssl_ca_certificate" =>  __DIR__."/global-bundle.pem"
]);