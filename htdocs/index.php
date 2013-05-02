<?php
try {
    
//error_reporting(0);
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler"); 
} else {
    ob_start();
}

session_start();


if (!isset($_SESSION['idsys_user'])) {
    header('Location: /login/');
}

require_once dirname(__FILE__).'/general.sys.inc.php';

$this_user = new User($_SESSION['idsys_user']);
    
$this_user->checkSession(session_id());

if (UNDER_MAINTENANCE) {
    header('Location: /logout.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="icon" href="images/sys/favicon.ico" sizes="16x16" type="image/ico" />
        <link rel="stylesheet" type="text/css" href="css/general.css"/>
        <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.ui-icons-colors.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.icons.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"/>
        <link rel="stylesheet" type="text/css" href="css/ColVis.css"/>
        <link rel="stylesheet" type="text/css" href="css/fileuploader.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.treetable.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css"/>
        <link rel="stylesheet" type="text/css" href="css/ui-progress-bar.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.jqplot.min.css"/>
        <title>Calidad de la Informaci&oacute;n</title>
        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.slidePanel.min.js"></script>
        <script type="text/javascript" src="js/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.scroller.js"></script>
        <script type="text/javascript" src="js/ColVis.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/jquery.zclip.min.js"></script>
        <script type="text/javascript" src="js/fileuploader.min.js"></script>
        <script type="text/javascript" src="js/jquery.treetable.js"></script>
        <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="js/globalize.js"></script>
        <script type="text/javascript" src="js/cultures/globalize.cultures.js"></script>
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type="text/javascript" src="js/general.js"></script>
        
        <script class="include" type="text/javascript" src="js/jquery.jqplot.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.cursor.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.pieRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.pointLabels.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.meterGaugeRenderer.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.logAxisRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasTextRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.pieRenderer.min.js"></script>
    </head>
    <body>
        <div id="maincontainer">
            <!-- header -->
            <div id="header">
               test test test 
            </div>
            <!-- content -->
            <div id="content">
                test test test 
            </div>
            <!-- footer -->
            <div id="footer">
                
                <span style="height: 50px; line-height: 50px;">test test test</span>
            </div>
        </div>
        <div id="windows"></div>
    </body>
</html>

<?php } catch(SysException $e) { ?>
<div><?php echo $e->getMessage(); ?></div>
<?php } ?>
