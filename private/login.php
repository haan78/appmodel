<?php
if (user::set()) {
    header("Refresh:0; url=/main");
    return;
}
user::clear();
page::vuePage("welcome",["com"=>"login", "message"=>"login fail"]);