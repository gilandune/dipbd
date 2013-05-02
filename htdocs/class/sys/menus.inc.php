<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * 
 * @author reuhtte
 */
class Menu {
    
    /**
     *
     * @var int 
     */
    public $idsys_menu;
    
    /**
     *
     * @var string 
     */
    public $title;
    
    /**
     * acción asociada al menu [none,load,link] 
     * @var string 
     */
    public $action;
    
    /**
     * archivo que se carga cuando la acción del menú es "load"
     * @var string
     */
    public $file;
    
    /**
     * Estatus del menu
     * @var Status 
     */
    public $status;
    
    /**
     *
     * @var int 
     */
    public $sys_module_idsys_module;


    /**
     * Constructor del objeto menu
     * @param int $idsys_menu
     * @throws SysException
     */
    public function __construct($idsys_menu) {
        if (empty($idsys_menu) || !filter_var($idsys_menu, FILTER_VALIDATE_INT)) {
            throw new SysException('id de usuario vacio o formato de entero no valido');
        }

        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_Menu_getProperties($idsys_menu);";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $this->idsys_menu = $res['idsys_menu'];
        $this->title      = $res['title'];
        $this->action     = $res['action'];
        $this->file       = $res['file'];
        $this->status     = new Status($res['sys_status_idsys_status']);
        $this->sys_module_idsys_module = $res['sys_module_idsys_module'];

        $db->disconnect();
    }
    
    /**
     * Devuelve todos los menus asociados a un modulo, sino se indica un modulo se obtienen todos los menus registrados en sistema
     * @param int $idsys_module
     * @return array
     * @throws SysException
     */
    public static function listMenus($idsys_module = 0) {
        
        if (empty($idsys_module) || !filter_var($idsys_module, FILTER_VALIDATE_INT)) {
            throw new SysException('id de modulo vacio o formato de entero no valido');
        }
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_listMenus($idsys_module);";
        
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
