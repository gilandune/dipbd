<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modules
 *
 * @author reuhtte
 */
class Module {
    
    /**
     * Id del modulo
     * @var int 
     */
    public $idsys_module;
    
    /**
     * Nombre corto del módulo
     * @var string 
     */
    public $name;
    
    /**
     * Descripción del módulo
     * @var string 
     */
    public $description;
    
    /**
     * Estatus del módulo
     * @var Status 
     */
    public $status;
    
    /**
     * 
     * @param int $idsys_module
     * @throws SysException
     */
    public function __construct($idsys_module) {
        if (empty($idsys_module) || !filter_var($idsys_module, FILTER_VALIDATE_INT)) {
            throw new SysException('id de modulo vacio o formato de entero no valido');
        }

        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_Module_getProperties($idsys_module);";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $this->idsys_module = $res['idsys_module'];
        $this->name         = $res['name'];
        $this->description  = $res['description'];
        $this->status       = new Status($res['sys_status_idsys_status']);

        $db->disconnect();
    }
    
    public static function listModules() {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_listModules();";
        
        $res = $db->queryAll($sp);
        
        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res[0]['_error']) && $res[0]['_error']) {
            throw new SysException($res[0]['_error_msg']);
        }

        $db->disconnect();
        
        return $res;
    }
}

?>
