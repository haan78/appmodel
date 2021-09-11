<?php

if ( isset($_COOKIE["SUBUTAI"]) ) {
    setcookie("SUBUTAI", "123", time() - (3600), "/");
    unset($_COOKIE["SUBUTAI"]);
    echo "silindi";
}

print_r($_COOKIE);