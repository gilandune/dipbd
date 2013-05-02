<?php
try {

session_start();

require_once dirname(__FILE__).'/../general.sys.inc.php';

if (!isset($_SESSION['idsys_user'])) {
    throw new SysException('Necesita iniciar sesión', null, true);
}

$this_user = new User($_SESSION['idsys_user']);

$this_user->checkSession(session_id());

if(!isset($_REQUEST['idsys_menu']) || empty($_REQUEST['idsys_menu'])) {
    throw new SysException('Opción de menu no válido');
}

$this_menu = new Menu($_REQUEST['idsys_menu']);

?>


<?php switch($this_menu->action): 
    case 'none': 
        $submenu_options = $this_user->getModuleMenus($this_menu->sys_module_idsys_module, 'tl', $this_menu->idsys_menu);
        $first_load = true;
        if ($submenu_options): ?>
        <div id="tabs">
            <ul>
            <?php foreach ($submenu_options as $submenu_option): ?>
                <li>
                    <a href="#tabs-<?php echo $submenu_option['idsys_menu']; ?>">
                        <span class="adm_submenu_title">
                            <?php echo utf8_encode($submenu_option['title']); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
            
            <?php foreach($submenu_options as $submenu_option): ?>
            <div id="tabs-<?php echo $submenu_option['idsys_menu']; ?>" load_file="<?php echo $submenu_option['file']; ?>">
                <?php 
                if ($submenu_option['action'] == 'load' && $first_load) { 
                    require_once dirname(__FILE__)."/{$submenu_option['file']}"; 
                    $first_load = false;
                } 
                ?>
            </div>
            <?php endforeach; ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $( "#tabs" ).tabs({
                    activate: function( event, ui ) {
                        abort_xhrcallbacks();
                        $(ui.oldPanel.selector).empty();
                        $('.ui-dialog').remove();
                        
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
                                $(ui.newPanel.selector).load('views/' + $(ui.newPanel.selector).attr('load_file'), 
                                function(){
                                    pnotify.pnotify_remove();
                                });
                            },
                            before_close: function() {
                                modal_overlay.fadeOut("fast", function(){
                                    $(this).remove()
                                });
                            }
                        });
                        
                    }
                });
            });
        </script>
        <?php endif;
        break; 
    case 'load':?>
        <div id="div_load_page">
            <?php require_once dirname(__FILE__)."/{$this_menu->file}"; ?>
        </div>
        <?php break;?>
<?php endswitch; ?>



<?php } catch(SysException $e) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $.pnotify({
            history: false,
            title: 'Error',
            text: '<?php echo $e->getMessage(); ?>',
            type: 'error',
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
