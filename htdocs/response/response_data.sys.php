<?php
require_once dirname(__FILE__).'/../general.sys.inc.php';

try {
    switch ($_REQUEST['opt']) {
        //<editor-fold defaultstate="collapsed" desc="login">
        case 'login':
            session_start();
            session_regenerate_id();
            
            $res = User::login($_REQUEST['username'], $_REQUEST['pswd'], $_SERVER['REMOTE_ADDR'], session_id());
            
            //$this_user = new User($res['idsys_user']);
            
            $_SESSION['idsys_user']        = $_REQUEST['username'];
//            $_SESSION['multisession']   = $this_user->multisession;
//            $_SESSION['idsys_session_log'] = $res['idsys_session_log'];
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Login correcto'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
        
        //<editor-fold defaultstate="collapsed" desc="new_user">
        case 'new_user':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(4);
                
            $params = array(
                'username'     => $_POST['fcu_username'],
                'first_name'   => $_POST['fcu_first_name'],
                'last_name'    => $_POST['fcu_last_name'],
                'email'        => $_POST['fcu_email'],
                'pswd'         => $_POST['fcu_pswd'],
                'confirm_pswd' => $_POST['fcu_confirm_pswd'],
                'multisession' => $_POST['fcu_multisession']
            );

            User::createUser($params);

            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Usuario registrado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="edit_user">
        case 'edit_user':

            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(5);
            
            $this_edit_user = new User($_POST['feu_idsys_user']);
            
            $params = array(
                'username'     => $_POST['feu_username'],
                'first_name'   => $_POST['feu_first_name'],
                'last_name'    => $_POST['feu_last_name'],
                'email'        => $_POST['feu_email'],
                'new_pswd'     => $_POST['feu_new_pswd'],
                'confirm_pswd' => $_POST['feu_confirm_pswd'],
                'multisession' => $_POST['feu_multisession']
            );
              
            $this_edit_user->update($params);
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Usuario Actualizado'
            );
            
            echo json_encode($return);
            
            break;
        //</editor-fold>
         
        //<editor-fold defaultstate="collapsed" desc="change_pswd">
        case 'change_pswd':

            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(44);
            
            $this_edit_user = new User($_SESSION['idsys_user']);
            
            $params = array(
                'current_pswd' => $_POST['fcp_current_pswd'],
                'new_pswd'     => $_POST['fcp_new_pswd'],
                'confirm_pswd' => $_POST['fcp_confirm_pswd']
            );
              
            $this_edit_user->changeMyPswd($params);
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Contraseña Actualizada'
            );
            
            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="delete_user">
        case 'delete_user':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(6);
            
            $this_edit_user = new User($_POST['idsys_user']);
            
            $this_edit_user->delete();
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Usuario Eliminado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="user_assign_role">
        case 'user_assign_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(7);
            
            $this_edit_user = new User($_POST['idsys_user']);
            
            $this_edit_user->assignRole($_POST['idsys_role']);
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Rol de usuario activado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="user_unassign_role">
        case 'user_unassign_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(7);
            
            $this_edit_user = new User($_POST['idsys_user']);
            
            $this_edit_user->unassignRole($_POST['idsys_role']);
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Rol de usuario desactivado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="new_role">
        case 'new_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(31);
                
            $params = array(
                'name'        => $_POST['fcr_name'],
                'description' => $_POST['fcr_description']
            );

            Role::createRole($params);

            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Nuevo Rol registrado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="edit_role">
        case 'edit_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(32);
            
            $this_edit_role = new Role($_POST['fer_idsys_role']);
            
            $params = array(
                'name'        => $_POST['fer_name'],
                'description' => $_POST['fer_description']
            );
              
            $this_edit_role->update($params);
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Rol Actualizado'
            );
            
            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="delete_role">
        case 'delete_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(33);
            
            $this_edit_role = new Role($_POST['idsys_role']);
            
            $this_edit_role->delete();
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Rol Eliminado de Sistema'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="deactivate_role">
        case 'deactivate_role':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(35);
            
            $this_edit_role = new Role($_POST['idsys_role']);
            
            $this_edit_role->deactivate();
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Rol Desactivado para todos los usuarios'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="role_assign_right">
        case 'role_assign_right':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(34);
            
            $this_edit_role = new Role($_POST['idsys_role']);
            
            $this_edit_role->assignRight($_POST['idsys_right']);
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Permiso Activado para el Rol'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="role_unassign_right">
        case 'role_unassign_right':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(34);
            
            $this_edit_role = new Role($_POST['idsys_role']);
            
            $this_edit_role->unassignRight($_POST['idsys_right']);
                    
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Permiso Desactivado para el Rol'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
            
        //<editor-fold defaultstate="collapsed" desc="list_menus">
        case 'list_menus':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

//            $this_user->checkRight(34);
            
            $this_menus = Menu::listMenus($_POST['idsys_module']);
            
            foreach ($this_menus as $index => $row) {
                $this_menus[$index]['title'] = utf8_encode($row['title']);
            }
            
//            echo '<pre>';
//            print_r($this_menus);
//            echo '</pre>';
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Lista de menus recuperada',
                'menus'   => $this_menus
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
        
        //<editor-fold defaultstate="collapsed" desc="new_right">
        case 'new_right':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(31);
                
            $params = array(
                'name'        => $_POST['fcr_name'],
                'description' => $_POST['fcr_description'],
                'type'        => $_POST['fcr_type'],
                'idsys_module'=> $_POST['fcr_module'],
                'idsys_menu'  => $_POST['fcr_menu']
            );

            Right::createRight($params);

            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Nuevo Permiso registrado'
            );

            echo json_encode($return);
            
            break;
        //</editor-fold>
         
        //<editor-fold defaultstate="collapsed" desc="edit_right">
        case 'edit_right':
            session_start();
            
            if (!isset($_SESSION['idsys_user'])) {
                throw new SysException('Necesita iniciar sesión', null, true);
            }
            
            $this_user = new User($_SESSION['idsys_user']);

            $this_user->checkRight(40);
            
            $this_edit_right = new Right($_POST['fer_idsys_right']);
            
            $params = array(
                'name'        => $_POST['fer_name'],
                'description' => $_POST['fer_description'],
                'type'        => $_POST['fer_type'],
                'idsys_module'=> $_POST['fer_module'],
                'idsys_menu'  => $_POST['fer_menu']
            );
              
            $this_edit_right->update($params);
            
            $return = array(
                'result'  => 'ok',
                'sys_msg' => 'Permiso Actualizado'
            );
            
            echo json_encode($return);
            
            break;
        //</editor-fold>
        
        default:
            throw new SysException('no se indico una opcion valida');
            break;
    }
}
catch (SysException $e) {
    $return = array(
        'result'    => 'error',
        'error_msg' => utf8_encode($e->getMessage()),
        'logout'    => $e->getLogout(),
        'debug_info'=> SYS_DEBUG ? array(
            'trace'      => $e->getTrace(),
            'extra_data' => $e->getDebugInfo()
        ) : ''
    );
    
    echo json_encode($return);
}
?>
