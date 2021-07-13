<?php

require_once __DIR__ . "/../lib/Web/Session.php";
require_once __DIR__ . "/db.php";

use \Web\SessionDefault;
use Web\Ticket;

class User {
    public static function validate() : bool {

        $user = (new SessionDefault())->get("email");
        if ($user) {
            return true;
        } else {
            //var_dump($_SESSION); exit();
            return false;
        }
    }

    public static function jsonValidate(bool $ticket = false) : void {
        $s = new SessionDefault();
        if ( $s->get("email") ) {
            if ($ticket) {
                if (!Ticket::pass($s)) {
                    throw new Exception("Ticket Failed!");
                }
            }
        } else {
            throw new Exception("Auth Failed!");
        }
    }

    public static function login(string &$message): bool {
        if (isset($_POST["email"])) {
            $s = new SessionDefault();
            $email = filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL);
            $pass = md5( filter_input(INPUT_POST,"pass",FILTER_DEFAULT) );
            $captcha =  isset($_POST["captcha"]) ? trim( $_POST["captcha"]) : "";
            if ( $captcha == $s->get("captcha") ) {
                $result = db::mongo()->selectDatabase("Test1")->selectCollection("User")->findOne(["email"=>$email, "pass"=>$pass]);
                if ( !is_null ($result)) {
                    $s->set("email",$email);
                    setcookie("UserName",$result["user"],time()+3600);
                    $message = "Go to main!";
                    return true;
                } else {
                    //$message = $pass." / ".$email;
                    $message = "User name or password is wrong";
                }
            } else {
                $message = "Captcha is wrong!";
            }            
        } else {
            $message = "Post request is required";
        }
        return false;
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
