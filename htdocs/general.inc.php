<?php

require_once dirname(__FILE__).'/../config/config.inc.php';

require_once dirname(__FILE__).'/class/db.inc.php';
require_once dirname(__FILE__).'/class/sys_exception.inc.php';


function month_name($month) {
    switch($month) {
        case 1: return 'Enero'; break;
        case 2: return 'Febrero'; break;
        case 3: return 'Marzo'; break;
        case 4: return 'Abril'; break;
        case 5: return 'Mayo'; break;
        case 6: return 'Junio'; break;
        case 7: return 'Julio'; break;
        case 8: return 'Agosto'; break;
        case 9: return 'Septiembre'; break;
        case 10: return 'Octubre'; break;
        case 11: return 'Noviembre'; break;
        case 12: return 'Diciembre'; break;
    }
}
?>
