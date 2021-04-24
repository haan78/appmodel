<?php
require_once __DIR__ . "/../lib/Web/Ticket.php";
require_once __DIR__ . "/../lib/Web/Session.php";
require_once __DIR__ . "/../vendor/autoload.php";

use \Web\Ticket;
use \Web\SessionDefault;

class page {

    private static function encodeMetaData(array $metadata): string {
        $key = hash("sha256", date("YmdHis") . (string)openssl_random_pseudo_bytes(40) . uniqid());
        $md = $metadata;
        $md["exp"] = time() + 60;
        $token = \Firebase\JWT\JWT::encode($md, $key);
        setcookie("SUBUTAI", $key, time() + 900);
        return $token;
    }


    public static function template(string $entry, array $args = []) {
        $session = new SessionDefault();
        $md = array_merge(["__TICTKE__" => (new Ticket($session))->save()], $args);
        $jwt = static::encodeMetaData($md);
        $rnd = uniqid();
        ob_start();
?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta name="SUBUTAI" content="<?php echo $jwt; ?>">
            <meta charset='utf-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <title>SUBUTAI</title>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
            <link href="css/chunk-vendors.css?<?php echo $rnd; ?>" rel="preload" as="style" />
            <link href="css/chunk-vendors.css?<?php echo $rnd; ?>" rel="stylesheet" />
            <link href="css/<?php echo $entry; ?>.css?<?php echo $rnd; ?>" rel="preload" as="style" />
            <link href="css/<?php echo $entry; ?>.css?<?php echo $rnd; ?>" rel="stylesheet" />
            <link href="js/chunk-vendors.js?<?php echo $rnd; ?>" rel="preload" as="script" />
            <link href="js/<?php echo $entry; ?>.js?<?php echo $rnd; ?>" rel="preload" as="script" />
        </head>

        <body onload=" __READY__() ">
            <div id="app" style="display: none;"></div>
            <div id="__LOAD__">Please Wait...<div>
                    <script>
                        function __READY__() {
                            document.getElementById("__LOAD__").remove();
                            document.getElementById("app").style.display = "block";
                        }
                    </script>
                    <script src="js/chunk-vendors.js?<?php echo $rnd; ?>"></script>
                    <script src="js/<?php echo $entry; ?>.js?<?php echo $rnd; ?>"></script>
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
