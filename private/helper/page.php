<?php
require_once __DIR__ . "/../lib/Web/Ticket.php";
require_once __DIR__ . "/../lib/Web/Session.php";

use \Web\Ticket;
use \Web\SessionDefault;

class page {


    public static function template(string $entry, array $args = []) {
        $session = new SessionDefault();
        Ticket::save($session);
        $rnd = uniqid();
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
            <link href="css/chunk-vendors.css?<?php echo $rnd; ?>" rel="preload" as="style" />
            <link href="css/chunk-vendors.css?<?php echo $rnd; ?>" rel="stylesheet" />
            <link href="js/chunk-vendors.js?<?php echo $rnd; ?>" rel="preload" as="script" />
            <script src="js/chunk-vendors.js?<?php echo $rnd; ?>"></script>
            <link href="css/<?php echo $entry; ?>.css?<?php echo $rnd; ?>" rel="preload" as="style" />
            <link href="css/<?php echo $entry; ?>.css?<?php echo $rnd; ?>" rel="stylesheet" />
            
            <link href="js/<?php echo $entry; ?>.js?<?php echo $rnd; ?>" rel="preload" as="script" />
            
            
            <script src="js/<?php echo $entry; ?>.js?<?php echo $rnd; ?>"></script>
        </head>

        <body>
            <div>Please Wait...<div>
            <script>window["__DATA__"] = <?php echo json_encode($args); ?>;</script>
        </body>

        </html>
    <?php
        ob_end_flush();
    }


    public static function error(Exception $ex) {
        ob_start();
    ?>
        <!DOCTYPE html>
        <html>

        <head profile="http://www.w3.org/2005/10/profile">
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
<?php
        ob_end_flush();
    }
}
