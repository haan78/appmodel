<?php

use \Web\Web;

Web::errorHandler(function (Exception $ex) {
    ob_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>SUBUTAI</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    </head>

    <body>
        <h2>Error</h2>
        <ul style="color: red;">
            <li><b>Message:</b><?php echo $ex->getMessage(); ?></li>
            <li><b>File:</b><?php echo $ex->getFile(); ?></li>
            <li><b>Line:</b><?php echo $ex->getLine(); ?></li>
        </ul>
    </body>

    </html><?php
            ob_end_flush();
        });
