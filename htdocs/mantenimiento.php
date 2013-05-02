<?php

require_once dirname(__FILE__).'/general.inc.php';

if (!UNDER_MAINTENANCE) {
    header('Location: /');
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <base href="<?php echo BASE_URL; ?>" />
    <link rel="icon" href="images/sys/favicon.ico" sizes="16x16" type="image/ico" />
    <link rel="stylesheet" type="text/css" href="css/general.css"/>
    <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css"/>
    <title>Calidad de la Informaci&oacute;n | En Mantenimiento</title>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
</head>
<body>
    <!-- header -->
    <div id="header">
        <img src="images/sys/infonavit.png" alt=""/>
        <img src="images/sys/simpleLogo.png" alt=""/>
    </div>
    <!-- content -->
    <div id="content">
        <div style="margin: 35px 10px 10px 10px; text-align: center;">
            <p style="font-size: 2em; font-weight: bold;">
                El sitio se encuentra temporalmente en mantenimiento,
                <br/>
                disculpe las molestias que esto le ocasione.
                <br/>
                <br/>
                Volveremos pronto
            </p>
            <img src="images/sys/enconstruccion.jpg" />
        </div>
    </div>
</body>
</html>