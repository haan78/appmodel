<?php

namespace Web {
    class ErrorPage {

        public static function handler( string $htmlFile ) {


            error_reporting(E_ALL);
            //ini_set('display_errors', TRUE);
            ini_set('display_errors', FALSE);
            ini_set('display_startup_errors', TRUE);


            register_shutdown_function(function() use($htmlFile) {
                $er = error_get_last();
                if (!is_null($er) && $er["type"] == E_ERROR) {
                    $type = $er["type"];
                    $message = $er["message"];
                    $file = $er["file"];
                    $line = $er["line"];
                    if (file_exists($htmlFile)) {
                        echo str_replace(["<!--TYPE-->","<!--MESSAGE-->","<!--FILE-->","<!--LINE-->"],[$type,$message,$file,$line],file_get_contents($htmlFile));
                    } else {
                        ?>
                        <span style="color: red;">Template file not found / <?php echo $htmlFile; ?></span><br/>
                        <ul>
                            <li><b>Error:</b> <?php echo $message; ?></li>
                            <li><b>Type:</b> <?php  echo $type; ?></li>
                            <li><b>File:</b> <?php echo $file; ?></li>
                            <li><b>Line:</b> <?php echo $line; ?></li>
                        </ul>
                        <?php
                    }
                                        
                }
            });
        }
    }
}