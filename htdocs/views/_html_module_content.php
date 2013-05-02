<?php
try {

session_start();

require_once dirname(__FILE__).'/../general.sys.inc.php';

if (!isset($_SESSION['idsys_user'])) {
    throw new SysException('Necesita iniciar sesión', null, true);
}

$this_user = new User($_SESSION['idsys_user']);

$this_user->checkSession(session_id());

$this_menu_topleft  = $this_user->getModuleMenus($_REQUEST['idsys_module'],'tl');
$this_menu_topright = $this_user->getModuleMenus($_REQUEST['idsys_module'],'tr');


?>

<div style="margin: 35px 10px 10px 10px;">
    <div id="main_menu">
        <?php if (!empty($this_menu_topleft)): ?>
        <div style="float: left; display: inline-block;">
            <?php foreach ($this_menu_topleft as $option): ?>
            <div class="mm_elem" idsys_menu="<?php echo $option['idsys_menu']; ?>" >
                <?php echo utf8_encode($option['title']); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        

        <?php if (!empty($this_menu_topright)): ?>
        <div style="float: right; display: inline-block;">
            <?php foreach ($this_menu_topright as $option): ?>
            <div class="mm_elem" idsys_menu="<?php echo $option['idsys_menu']; ?>" >
                <?php echo utf8_encode($option['title']); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
    <div id="subcontent" >
        &nbsp;
        <?php // require_once dirname(__FILE__).'/_html_sub_resumen.php';?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
//        $(document).tooltip();
                
        $('div.mm_elem').click(function(){
            $('#subcontent').empty();
            
            var mm_opt_title = $(this).text();
            var idsys_menu = $(this).attr('idsys_menu');
            $('#main_header_current_mm_opt')
            .removeClass('hidden')
            .text(mm_opt_title);
            
            $(".ui-dialog").remove();
        
            $('#windows').empty();
            
            var modal_overlay;
            
            $.pnotify({
                title  : "Cargando",
                type   : "info",
                mouse_reset: false,
                history: false,
                stack  : false,
                closer : true,
                hide   : false,
                before_open: function(pnotify) {
                    abort_xhrcallbacks();
                    // Position this notice in the center of the screen.
                    pnotify.css({
                        "top": ($(window).height() / 2) - (pnotify.height() / 2),
                        "left": ($(window).width() / 2) - (pnotify.width() / 2)
                    });
                    // Make a modal screen overlay.
                    if (modal_overlay) modal_overlay.fadeIn("fast");
                    else modal_overlay = $("<div />", {
                        "class": "ui-widget-overlay",
                        "css": {
                            "display": "none",
                            "position": "fixed",
                            "top": "0",
                            "bottom": "0",
                            "right": "0",
                            "left": "0"
                        }
                    }).appendTo("body").fadeIn("fast");
                },
                after_open: function(pnotify){
                    $('#subcontent').load('/views/_html_subcontent.php', {
                        'idsys_menu': idsys_menu
                    }, function(){
                        pnotify.pnotify_remove();
                    });
                },
                before_close: function(pnotify) {
                    modal_overlay.fadeOut("fast", function(){
                        $(this).remove()
                    });
                }
            });
            
        });
    });
</script>

<?php } catch(SysException $e) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $.pnotify({
            history: false,
            title  : 'Error',
            text   : '<?php echo $e->getMessage(); ?>',
            type   : 'error',
            styling: 'jqueryui',
//            nonblock: true,
            delay: 2000
        });
          
        <?php 
        
        if (SYS_DEBUG):
            
        $array_debug = array(
            'trace'      => $e->getTrace(),
            'extra_data' => $e->getDebugInfo()
        ); 
        
        $json_debug = addslashes(str_replace('\n', '', json_encode($array_debug)));
        
        ?>
        
        var debug_data = jQuery.parseJSON('<?php echo $json_debug; ?>');

        console.log(debug_data);
        
        <?php endif; ?>
            
        <?php if ($e->getLogout() && !SYS_DEBUG): ?>
        setTimeout(function(){
            $(location).attr('href','logout.php');
        },4000);
        <?php endif; ?>
    });
</script>
<?php } ?>



