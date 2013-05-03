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

//$this_user = new User($_SESSION['idsys_user']);
//    
//$this_user->checkSession(session_id());

if (UNDER_MAINTENANCE) {
    header('Location: /logout.php');
}

$db = new DB('Franquicias');
$sp = "CALL SP_Orders({$_SESSION['idsys_user']});";
$res = $db->queryRow($sp);

$db2 = new DB('Franquicias');
$sp2 = "CALL SP_top_sales();";
$res2 = $db2->queryAll($sp2);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <base href="<?php echo BASE_URL; ?>" />
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
        <title>Diplomado Base de Datos</title>
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
                
            </div>
            <!-- content -->
            <div id="content">
                <div id="tabs">
                    <ul>
                        <li>
                            <a href="#tabs-ordenes">
                                <span class="adm_submenu_title">
                                    Ordenes
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-ventas">
                                <span class="adm_submenu_title">
                                    Ventas
                                </span>
                            </a>
                        </li>
                    </ul>

                    <div id="tabs-ordenes">
                        <table id="orders_table" class="altertable">
                            <thead>
                                <th>cliente</th>
                                <th>ordenes recibidas</th>
                                <th>ordenes enviadas</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $res['cno']; ?></td>
                                    <td><?php echo $res['received']; ?></td>
                                    <td><?php echo $res['shipped']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <br/>
                        <div id="jqplot_global_id_cop_bars" style="height:300px; width:400px;"></div>
                        
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var this_data3 = new Array();
                                var this_ticks3 = new Array();

                                this_data3.push('<?php echo $res['received']; ?>');
                                this_data3.push('<?php echo $res['shipped']; ?>');

                                this_ticks3.push('Recibidas');
                                this_ticks3.push('Enviadas');

                                $.jqplot.config.enablePlugins = true;

                                plot3 = $.jqplot('jqplot_global_id_cop_bars', [this_data3], {
                                    title: 'Ordenes',
                                    animate: !$.jqplot.use_excanvas,

                                    seriesDefaults:{
                                        renderer:$.jqplot.BarRenderer,
                                        pointLabels: { show: true },
                                        rendererOptions: {
                                            varyBarColor : true,
                                            barDirection: 'vertical',
                                            barMargin: 4,
                                            barPadding: 4
                                        }
                                    },
                                    axes: {
                                        xaxis: {
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions:{ 
                        //                        angle: -30,
                                                fontSize: '10pt',
                                                markSize:15
                                            },
                                            ticks: this_ticks3

                                        },
                                        yaxis: {min: 0, max: 5}
                                    },
                                    highlighter : { show: false },
                                    cursor: {
                                        show: false
                                    }
                                });
                            });
                        
                        </script>
                        
                    </div>
                    <div id="tabs-ventas">
                        <table id="sales_table" class="altertable">
                            <thead>
                                <th>vendedor</th>
                                <th>apellido</th>
                                <th>monto</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $res2[0]['eno']; ?></td>
                                    <td><?php echo utf8_encode($res2[0]['ename']); ?></td>
                                    <td><?php echo $res2[0]['sales']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $res2[1]['eno']; ?></td>
                                    <td><?php echo utf8_encode($res2[1]['ename']); ?></td>
                                    <td><?php echo $res2[1]['sales']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <br/>
                        <div id="jqplot_global_sales_bars" style="height:300px; width:400px;"></div>
                        
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var this_data4 = new Array();
                                var this_ticks4 = new Array();

                                this_data4.push('<?php echo $res2[0]['sales']; ?>');
                                this_data4.push('<?php echo $res2[1]['sales']; ?>');

                                this_ticks4.push('<?php echo utf8_encode($res2[0]['ename']); ?>');
                                this_ticks4.push('<?php echo utf8_encode($res2[1]['ename']); ?>');

                                $.jqplot.config.enablePlugins = true;

                                plot4 = $.jqplot('jqplot_global_sales_bars', [this_data4], {
                                    title: 'Ordenes',
                                    animate: !$.jqplot.use_excanvas,

                                    seriesDefaults:{
                                        renderer:$.jqplot.BarRenderer,
                                        pointLabels: { show: true },
                                        rendererOptions: {
                                            varyBarColor : true,
                                            barDirection: 'vertical',
                                            barMargin: 4,
                                            barPadding: 4
                                        }
                                    },
                                    axes: {
                                        xaxis: {
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions:{ 
                        //                        angle: -30,
                                                fontSize: '10pt',
                                                markSize:15
                                            },
                                            ticks: this_ticks4

                                        },
                                        yaxis: {min: 0, max: 300}
                                    },
                                    highlighter : { show: false },
                                    cursor: {
                                        show: false
                                    }
                                });
                                
                                $('#tabs').bind('tabsshow', function(event, ui) {
                                    if (ui.index === 0 && plot3._drawCount === 0) {
                                      plot3.replot();
                                    }
                                    else if (ui.index === 1 && plot4._drawCount === 0) {
                                      plot4.replot();
                                    }
                                });
                            });
                        
                        </script>
                    </div>
                </div>
            </div>
            <!-- footer -->
            <div id="footer">
                
                <span style="height: 50px; line-height: 50px;"></span>
            </div>
        </div>
        <div id="windows"></div>
    </body>
</html>

<?php } catch(SysException $e) { ?>
<div><?php echo $e->getMessage(); ?></div>
<?php } ?>
