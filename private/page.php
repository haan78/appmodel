<?php
require_once "lib/Web/Page.php";
class page extends \Web\Page
{
    protected static function template($preload, $stylesheet, $metadata, $script): void
    {
?>
        <!DOCTYPE html>
        <html lang="tr">

        <head>
            <title>SUBUTAI</title>
            <meta charset='utf-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel="icon" href="/favicon.ico">
            <?php echo $preload . $stylesheet . $metadata; ?>
        </head>

        <body>
            <div id="app"></div>
            <?php echo $script; ?>
        </body>

        </html>
<?php
    }
}
