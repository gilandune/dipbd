<?php
try {

session_start();

require_once dirname(__FILE__).'/../general.sys.inc.php';

if (!isset($_SESSION['idsys_user'])) {
    throw new SysException('Necesita iniciar sesión', null, true);
}

$this_user = new User($_SESSION['idsys_user']);

$this_user->checkSession(session_id());

$main_modules = $this_user->getModules('t');

?>

<div style="margin: 10px;">
    <div id="main_modules_trigger" class="trigger left">M&oacute;dulos</div>
    <div id="main_modules" class="panel left">
        <div id="main_modules_list">
        <?php 
        if(!empty($main_modules)):
            foreach ($main_modules as $main_module):
        ?>
            <div class="main_modules_item" idsys_module="<?php echo $main_module['idsys_module']; ?>" title="click para cargar el m&oacute;dulo">
                <div class="main_modules_arrow">&nbsp;</div>
                <div class="main_modules_title">
                <?php echo utf8_encode($main_module['name']); ?>
                </div>
            </div>
        <?php 
            endforeach; 
        endif; 
        ?>
        </div>
    </div>

    <div id="module_content">
        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('#main_modules').slidePanel({
		triggerName: '#main_modules_trigger',
		triggerTopPos: '35px',
		panelTopPos: '73px',
                panelOpacity: 0.8
//		ajax: true,
//		ajaxSource: 'ajax.html'
	});
        
        $('.main_modules_item').click(function(){
            var main_module_title = $(this).children('.main_modules_title').text();
            
            $('#main_header_current_module')
            .removeClass('hidden')
            .text(main_module_title);
            
            $('#main_header_current_mm_opt')
            .text('')
            .addClass('hidden');
            
            $(".ui-dialog").remove();
        
            $('#windows').empty();
            
            $('#module_content').load('views/_html_module_content.php', {
                idsys_module: $(this).attr('idsys_module')
            });
        });
        
//        $('.main_modules_item').tooltip({ position: { my: "left", at: "right center" } });
    });
</script>

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
