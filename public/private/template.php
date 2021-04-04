<?php
if (!isset($status)) {
    exit();
} elseif ( $status == 4 ) {
    $_SESSION["status"] = 0;
    header("Refresh:0");
    exit();
} else {
    ob_start(); 
}?><!DOCTYPE html>
<html lang="tr">

<head profile="http://www.w3.org/2005/10/profile">
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="backend" content="<?php echo "$token|$key"; ?>">
	<title>SUBUTAI</title>
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon-96x96.png">
</head>

<body>
    <div id="app"></div>
    <script>
        <?php echo file_get_contents(__DIR__ . "/js/" . ($status == 0 ? "app.js" : "login.js")); ?>
    </script>
</body>

</html>
<?php ob_end_flush();
