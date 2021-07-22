<?php

require_once __DIR__ . "/../lib/Web/Session.php";
require_once __DIR__ . "/../lib/Web/SecureClient.php";
require_once __DIR__ . "/db.php";

use \Web\SessionDefault;

class User {
    public static function validate() : bool {
        return \Web\SecureClient::check(new SessionDefault());
    }

    public static function jsonValidate() : void {
        \Web\SecureClient::validate(new SessionDefault(),"Auth Failed!");
    }

    public static function htmlValidate(string $url) : void {
        if (!static::validate()) {
            header("Location: $url");
            exit();
        }
    }

    public static function jwt(string $tokenkey) : int {
        $token = filter_input(INPUT_POST,$tokenkey,FILTER_SANITIZE_SPECIAL_CHARS);
        if ( is_string($token) ) {
            return 2; //fail not yet
        } else {
            return 1; // no tokken
        }
    }

    public static function post(string $userkey, string $passkey, string $captchakey = "captcha") : int {
        $user = filter_input(INPUT_POST,$userkey,FILTER_SANITIZE_SPECIAL_CHARS);
        $pass = md5( filter_input(INPUT_POST,$passkey,FILTER_DEFAULT) );
        $captcha = filter_input(INPUT_POST,$userkey,FILTER_SANITIZE_SPECIAL_CHARS);
        if ( is_string($user) && is_string($pass) ) {
            $s = new SessionDefault();
            if ( !is_string($captcha) || $captcha == $s->get($captchakey,"?") ) {
                $result = static::userData($user,$pass);
                if (!is_null($result)) {
                    static::setCookie($result);
                    $s->set("METHOD","POST");
                    $s->set($userkey,$user);
                    return 0; // Ok
                } else {
                    return 3; // Auth Fail
                }
            } else {
                return 2; // Captcha fail
            }
        } else { 
            return 1; //No Post
        }
    }

    public static function log(string $aciton): void {
        $s = new SessionDefault();
        $data = [
            "session" => $s->stack(),
            "action" => $aciton
        ];
        db::mongo()->selectDatabase("Test1")->selectCollection("Log")->insertOne($data);
    }

    private static function userData($user,$pass = false) {
        $arr=[
            "user"=>$user
        ];
        if (is_string($pass)) {
            $arr["pass"] = $pass;
        }
        return db::mongo()->selectDatabase("Test1")->selectCollection("User")->findOne($arr);
    }

    private static function setCookie($data) : void {
        setcookie("UserName", $data["user"], time()+3600);
    }

    public static function clear() {
        $s = new SessionDefault();
        $s->clear();
    }

    public static function kill() {
        $s = new SessionDefault();
        $s->kill();
    }
}
