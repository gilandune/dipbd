<?php
try {

session_start();

require_once dirname(__FILE__).'/../general.sys.inc.php';

if (!isset($_SESSION['idsys_user'])) {
    throw new SysException('Necesita iniciar sesión', null, true);
}

$this_user = new User($_SESSION['idsys_user']);

$this_user->checkSession(session_id());


?>

<div class="main_header">
    <ul class="main_header_left">
        <li class="main_header_opt">
            <strong><span style="color:#ff6666;">DIRSA</span></strong> - Calidad de la Informaci&oacute;n
        </li>
        <li id="main_header_current_module" class="main_header_opt hidden">
        </li>
        <li id="main_header_current_mm_opt" class="main_header_opt hidden">
        </li>
    </ul>
    <ul class="main_header_right">
        <li class="main_header_opt">
            <span style="color:#ff6666;">Bienvenido:&nbsp;</span>
            <?php
                echo utf8_encode("$this_user->name"); 
            ?>
        </li>
        
        <li class="main_header_opt_h" onclick="$(location).attr('href','logout.php');">
            cerrar sesi&oacute;n
            <span class="ui-icon grey ui-icon-power" style="display: inline-block; vertical-align: middle; margin-top: -2px;">&nbsp;</span>
        </li>
    </ul>
</div>

<?php } catch(SysException $e) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $.pnotify({
            history: false,
            title: 'Error',
            text: '<?php echo $e->getMessage(); ?>',
            type: 'success',
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
