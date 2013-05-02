<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of status
 *
 * @author reuhtte
 */
class Status {
    
    /**
     * id del estatus en sistema
     * @var int 
     */
    public $idsys_status;
    
    /**
     * nombre del estatus
     * @var string
     */
    public $name;
    
    /**
     * descripcion del estatus
     * @var string
     */
    public $description;
    
    /**
     * Define si el estatus es de bloqueo
     * @var bool
     */
    public $block;
    
    public function __construct($idsys_status) {
        
        if (empty($idsys_status) || !filter_var($idsys_status, FILTER_VALIDATE_INT)) {
            throw new SysException('id de estatus vacio o formato de entero no valido');
        }

        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_Status_getProperties($idsys_status);";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $this->idsys_status = $res['idsys_status'];
        $this->name         = $res['name'];
        $this->description  = $res['description'];
        $this->block        = $res['block'];

        $db->disconnect();
    }
}

?>
