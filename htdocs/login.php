<?php
//error_reporting(0);
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler"); 
} else {
    ob_start();
}

session_start();

require_once dirname(__FILE__).'/general.inc.php';

if (UNDER_MAINTENANCE) {
    header('Location: /mantenimiento/');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <base href="<?php echo BASE_URL; ?>" />
    <link rel="icon" href="images/sys/favicon.ico" sizes="16x16" type="image/ico" />
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.reject.css"/>
    <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.ui-icons-colors.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.icons.css"/>
    <title>Calidad de la Informaci&oacute;n | Acceso</title>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/jquery.reject.js"></script>
    <script type="text/javascript" src="js/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="js/general.js" ></script>
    <script type="text/javascript" src="js/login.js" ></script>
</head>
<body>
    <div id="maincontainer">
        <!-- header -->
        <div id="header">
            <img src="images/sys/infonavit.png" alt=""/>
            <img src="images/sys/simpleLogo.png" alt=""/>
        </div>
        <!-- content -->
        <div id="content">
            <form id="waform_login" name="waform_login" method="post">
                <div id="login_box">
                    <div id="login_box_title">Franquicias | Ingreso</div>
                    <br/>
                    <label for="username">Usuario</label>
                    <br/>
                    <input type="text" id="username" name="username"/>
                    <br/>
                    <label for="pswd">Password</label>
                    <br/>
                    <input type="password" id="pswd" name="pswd"/>
                    <br/>
                    <br/>
                    <input type="hidden" id="opt" name="opt" value="login"/>
                    <input type="submit" class="button" value="ingresar"/>
                </div>
                <div></div>
            </form>
        </div>
        <!-- footer -->
        <div id="footer">
            <span>Esta aplicaci&oacute;n funciona mejor con <a href="http://www.mozilla.org/es-MX/firefox/" target="_blank">Firefox</a>, <a href="http://www.google.com/chrome?hl=es" target="_blank">Chrome</a>, <a href="http://www.apple.com/mx/safari/" target="_blank">Safari</a> y <a href="http://www.opera.com/es-419/" target="_blank">Opera</a></span>
            <br/>
            El funcionamiento con Internet Explorer podría no ser el &oacute;ptimo.
            <br/>
            De ser necesario usar IE9+.
        </div>
    </div>
    <div id="windows"></div>
</body>
</html>
