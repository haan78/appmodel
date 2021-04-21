<?php
if (user::set()) {
    header("Refresh:0; url=/main");
    return;
}
page::vuePage("welcome",["com"=>"login", "message"=>"login fail"],$head,$body);

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