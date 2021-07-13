<?php

require_once __DIR__ . "/lib/Web/Ticket.php";
require_once __DIR__ . "/lib/Web/Session.php";
require_once __DIR__ . "/helper/user.php";


$rnd = uniqid();
$data = ["com"=>"forgot"];

ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>SUBUTAI</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon-96x96.png" />
    <link rel="stylesheet" href="assets/chunk-vendors.css?<?php echo $rnd; ?>" />
    <link rel="stylesheet" href="assets/welcome.css?<?php echo $rnd; ?>" />
</head>

<body>
    <div>Please Wait...</div>
    
    <script src="assets/chunk-vendors.js?<?php echo $rnd; ?>"></script>
    <script src="assets/welcome.js?<?php echo $rnd; ?>"></script>
    <script>
        __INIT__('<?php echo json_encode($data); ?>');
    </script>
</body>

</html><?php ob_end_flush();