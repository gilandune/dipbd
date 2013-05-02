<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sys
 *
 * @author reuhtte
 */
class Sys {
    
    /**
     * Registra un error de sistema en la base de datos
     * @param int $iduser
     * @param string $ip
     * @param string $sessionid
     * @param array $error_data Arreglo con el detalle del error
     * @return boolean
     */
    public static function Error($iduser, $ip, $sessionid, $error_data) {
//        $db = new DB('Auditorias');
//
//        $sp = "CALL SP_Sys_setError($iduser,'$ip','$sessionid')";
//
//        $res = $db->queryRow($sp);
//
//        if (PEAR::isError($res)) {
//            throw new Exception($res->getMessage());
//        }
//
//        if (isset($res['_error']) && $res['_error']) {
//            throw new Exception($res['_error_msg']);
//        }
//
//        $db->disconnect();

        return true;
    }
}

?>
