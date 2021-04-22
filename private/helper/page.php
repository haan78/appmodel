<?php
require_once __DIR__ . "/../lib/Web/Webpack.php";
require_once __DIR__ . "/../lib/Web/Ticket.php";
require_once __DIR__ . "/../lib/Web/Session.php";

use \Web\Ticket;
use \Web\SessionDefault;

class page
{
    public static function vuePage(string $script, array $args = []): void
    {
        function encodeMetaData(array $metadata, int $expire = 60): string
        {
            $key = hash("sha256", date("YmdHis") . (string)openssl_random_pseudo_bytes(40) . uniqid());
            $md = $metadata;
            $md["exp"] = time() + $expire;
            $token = \Firebase\JWT\JWT::encode($md, $key);
            return $token . "|" . $key;
        }
        $md = array_merge(["__TICTKE__" => (new Ticket(new SessionDefault()))->save()], $args);
        $str = encodeMetaData($md);
        \Web\webpack(ROOT, "css", "js", $script, $head, $body);
        $head = '<meta name="backend" content="' . $str . '">' . PHP_EOL . $head;
        static::template($head,$body);
    }

    public static function template($head, $body)
    {
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
            <?php echo $head; ?>
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
                    <?php echo $body; ?>
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
