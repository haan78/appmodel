<?php

function shutdownFunction(){
    echo "shutdownFunction is called <br/>\n";
    print_r(error_get_last());   
} 

function errorHandlerFunction(){
    echo "errorHandlerFunction is called <br/>\n";
} 
register_shutdown_function('shutdownFunction');
set_error_handler('errorHandlerFunction');

//echo "foo\n"; // scenario 1 no errors
//echo $undefinedVar; //scenario 2 error is triggered
//undefinedFunction(); //scenario 3 Fatal error is triggered
die();
throw new \Exception("aaaaa"); //scenario 4 exception is thrown