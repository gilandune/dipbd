<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sys_exception
 *
 * @author reuhtte
 */
class SysException extends Exception {
    /**
     * arreglo con datos extras
     * @var int 
     */
    protected $debuginfo;
    
    /**
     * determina si la excepción saca al usuario de sesión
     * @var bool
     */
    protected $logout;

    public function __construct($message, $debuginfo = array(), $logout = false,  $code = 0, Exception $previous = null) {

        $this->debuginfo = $debuginfo;
        $this->logout = $logout;

        parent::__construct($message, $code, $previous);

    }

    /**
     * Devuelte el arreglo con datos extras de debug
     * @return array
     */
    public function getDebugInfo(){
        return $this->debuginfo;
    }

    /**
     * Devuelve el arrego con datos extras de debug en formato JSON
     * @return string
     */
    public function getJSONDebugInfo() {
        return json_encode($this->debuginfo);
    }
    
    /**
     * Devuelve la propiedad logout para determinar si la excepción termina la sessión del usuario
     * @return bool
     */
    public function getLogout(){
        return $this->logout;
    }
}

?>
