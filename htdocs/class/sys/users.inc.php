<?php

/**
 * Description of users
 *
 * @author reuhtte
 */
class User {
    
    /**
     * id del usuario
     * @var int 
     */
    public $idsys_user;
    
    /**
     * usuario
     * @var string 
     */
    public $username;
    
    /**
     * nombre del usuario 
     * @var string
     */
    public $first_name;
    
    /**
     * apellidos del usuario
     * @var string 
     */
    public $last_name;
    
    /**
     * nombre completo del usuario
     * @var string 
     */
    public $name;
    
    /**
     * dirección de correo del usuario
     * @var type 
     */
    public $email;
    
    /**
     * identifica si el usuario puede iniciar sesión desde varios equipos
     * @var bool
     */
    public $multisession;
    
    /**
     * sesión asociada al usuario registrada en base de datos
     * @var string 
     */
    public $sessionid;
    
    /**
     *
     * @var string 
     */
    public $sys_session_id;
    
    /**
     * Objeto tipo Status
     * @var Status 
     */
    public $status;
    
    /**
     * Constructor del objeto usuario
     * @param int $iduser
     * @throws SysException
     */
    public function __construct($idsys_user) {
        if (empty($idsys_user) || !filter_var($idsys_user, FILTER_VALIDATE_INT)) {
            throw new SysException('id de usuario vacio o formato de entero no valido');
        }

        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_getProperties($idsys_user);";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $this->idsys_user   = $res['cno'];
        $this->username     = $res['username'];
        $this->first_name   = $res['first_name'];
        $this->last_name    = $res['last_name'];
        $this->email        = $res['email'];
        $this->name         = $this->first_name.' '.$this->last_name;
        $this->multisession = $res['multisession'];
        $this->sessionid    = $res['sessionid'];
        $this->status       = new Status($res['sys_status_idsys_status']);

        $db->disconnect();
    }
    
    /**
     * Función que controla el logueo de un usuario
     * @param string $username nombre de usuario
     * @param string $pswd password
     * @param string $ip ip desde la que se está realizando el intento de acceso
     * @param string $sessionid sesión de PHP
     * @return boolean
     * @throws SysException
     */
    public static function login($username, $pswd, $ip, $sessionid) {
            
        require_once dirname(__FILE__).'/../PasswordHash.php';

        //valida parametros vacios
        if (empty($username) || empty($pswd)) {
            session_destroy();
            throw new SysException('Usuario y/o Password vacío');
        }

        if (empty($ip) || empty($sessionid)) {
            session_destroy();
            throw new SysException('IP y/o ID de Sessión vacíos');
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            session_destroy();
            throw new SysException('Formato de IP no válido');
        }

        //valida usuario esté registrado en sistema y regresa el hash del pswd
        $check_login = User::checkLogin($username);

        $hasher = new PasswordHash(8, false);

        $login_status = 'success';

        //valida que el password sea correcto
        if (!$hasher->CheckPassword($pswd, $check_login['pswd'])) {
            $login_status = 'failed';
        }

        if ($login_status == 'failed') {
            //registra un inicio de sesión fallido
//            SysUser::failedLogin($username, json_encode(array(
//                'login_status' => $login_status,
//                'ip_address'   => $ip
//            )));
            session_destroy();
            throw new SysException('Usuario y/o Password incorrectos');
        }

        //inicia datos de sesión en base de datos
//        $idsys_session_log = User::initSession($check_login['idsys_user'], $ip, $sessionid);

        return array(
            'idsys_user'        => $check_login['cno'],
            'idsys_session_log' => $idsys_session_log
        );
    }
    
    /**
     * Valida que el usuario esté registrado en sistema
     * @param string $username
     * @return array
     * @throws SysException
     */
    private static function checkLogin($username) {
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_User_checkLogin('$username');";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            session_destroy();
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            session_destroy();
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();

        return $res;
    }
    
    /**
     * Inicia sesión del usuario en sistema
     * @param int $iduser id del usuario
     * @param string $ip ip desde donde inicia sesión el usuario
     * @param string $sessionid id de sesión de PHP asociada al usuario
     * @return array
     * @throws SysException
     */
    private static function initSession($idsys_user, $ip, $sessionid) {
        $db = new DB('Franquicias');
            
        $sp = "CALL SP_Sys_User_initSession($idsys_user,'$ip','$sessionid');";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            session_destroy();
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            session_destroy();
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return $res['idsys_session_log'];
    }
    
    /**
     * Registra un intento fallido de inicio de sesión
     * @param string $username usuario que intento el acceso al sistema
     * @param string $description descripcion del acceso fallido
     * @return array
     * @throws SysException
     */
    private static function failedLogin($username, $description) {
        $db = new DB('Franquicias');
            
        $sp = "CALL SP_Sys_User_failedLogin('$username','$description');";

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            session_destroy();
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            session_destroy();
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return $res;
    }
    
    /**
     * Valida la sesión del usuario contra la registrada en sistema
     * @param type $sessionid
     * @return boolean
     * @throws SysException
     */
    public function checkSession($sessionid) {
        
        if (!$this->multisession) {
        
            $db = new DB('Franquicias');

            $sp = "CALL SP_Sys_User_checkSession($this->idsys_user,'$sessionid');";

            $res = $db->queryRow($sp);

            if (PEAR::isError($res)) {
                session_destroy();
                throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo(), true);
            }

            if (isset($res['_error']) && $res['_error']) {
                session_destroy();
                throw new SysException($res['_error_msg'], null, true);
            }

            $db->disconnect();
        }
        
        return true;
    }
    
    /**
     * Obtiene los módulos a los que tiene accesso el usuario
     * @return array
     * @throws SysException
     */
    public function getModules($malign = 't') {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_getModules($this->idsys_user, '$malign');";

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
    
    /**
     * Devuelve los menus a los que tiene acceso el usuario
     * @param int $idsys_module identificador del modulo
     * @param string $position posicion en la pantalla [tl,tr,bl,br] => [top-left,top-right,bottom-left,bottom-right]
     * @param type $parent identificador del menu padre
     * @return array
     * @throws SysException
     */
    public function getModuleMenus($idsys_module, $position, $parent = 'null') {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_getModuleMenus($this->idsys_user, $idsys_module, '$position', $parent);";
        
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
    
    /**
     * Verifica que el usuario tenga el permiso solicitado
     * @param int $idsys_right identificardor del permiso en la base de datos
     * @return boolean
     * @throws SysException
     */
    public function checkRight($idsys_right) {
        
        if (empty($idsys_right) || !filter_var($idsys_right, FILTER_VALIDATE_INT)) {
            throw new SysException('id de permiso vacio o formato de permiso no valido');
        }
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_checkRight($this->idsys_user, $idsys_right);";
        
        $res = $db->queryRow($sp);
        
        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }
    
    /**
     * Devuelve la lista de usuarios datos de alta en sistema
     * @return type
     * @throws SysException
     */
    public static function listUsers() {
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_listUsers(0);";
        
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
    
    /**
     * Crea un nuevo usuario en sistema
     * @param array $params
     * @return boolean
     * @throws SysException
     */
    public static function createUser($params) {
        
        if (empty($params) || !is_array($params)) {
            throw new SysException('Error: parametros vacios');
        }

        require_once dirname(__FILE__).'/../PasswordHash.php';
        require_once dirname(__FILE__).'/../form.validator.inc.php';

        $db = new DB('Franquicias');

        $form_fields = array(
            'username'     => array('required'=>true,'conditions'=>array('length[3,20]')),
            'first_name'   => array('required'=>true,'conditions'=>array('alphanum','length[1,45]')),
            'last_name'    => array('required'=>true,'conditions'=>array('alphanum','length[1,65]')),
            'email'        => array('required'=>true,'conditions'=>array('email','length[1,65]')),
            'pswd'         => array('required'=>true,'conditions'=>array('alphanum','length[5,20]')),
            'confirm_pswd' => array('required'=>true,'conditions'=>array('confirm[pswd]')),
            'multisession' => array('required'=>true,'conditions'=>array(''))
        );

        $form = new FormValidator($form_fields, 'waform');

        $valid = $form->validate($params);

        if (!$valid) {
            throw new SysException(implode('<br/>', $form->getErrors()));
        }

        //encriptar contraseña
        $hasher = new PasswordHash(8, false);

        $pswd_hashed = $hasher->HashPassword($params['pswd']);

        if (strlen($pswd_hashed) < 20) {
            throw new SysException('Ocurrió un error al crear el usuario');
        }

        $sp = utf8_decode("CALL SP_Sys_createUser('{$params['username']}','{$pswd_hashed}','{$params['first_name']}','{$params['last_name']}','{$params['email']}',{$params['multisession']},1)");

        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();

        return true;
    }
    
    /**
     * Actualiza datos del usuario en sistema
     * @param array $params contiene todas las propiedades del objeto con los datos a actualizar
     * @return boolean
     * @throws SysException
     */
    public function update($params) {
        if (empty($params) || !is_array($params)) {
            throw new SysException('Error: parametros vacios');
        }
        
        require_once dirname(__FILE__).'/../PasswordHash.php';
        require_once dirname(__FILE__).'/../form.validator.inc.php';

        $form_fields = array(
            'username'     => array('required'=>true,'conditions'=>array('length[3,20]')),
            'first_name'   => array('required'=>true,'conditions'=>array('alphanum','length[1,45]')),
            'last_name'    => array('required'=>true,'conditions'=>array('alphanum','length[1,65]')),
            'email'        => array('required'=>true,'conditions'=>array('email','length[1,65]')),
            'new_pswd'     => array('required'=>false,'conditions'=>array('alphanum','length[5,20]')),
            'confirm_pswd' => array('required'=>false,'conditions'=>array('confirm[new_pswd]')),
            'multisession' => array('required'=>true,'conditions'=>array(''))
        );

        $form = new FormValidator($form_fields, 'waform');

        $valid = $form->validate($params);

        if (!$valid) {
            throw new SysException(implode('<br/>', $form->getErrors()));
        }
        
        // datos generales
        if ($this->username != utf8_decode($params['username']) ||
            $this->first_name != utf8_decode($params['first_name']) || 
            $this->last_name != utf8_decode($params['last_name']) || 
            $this->email != utf8_decode($params['email']) || 
            $this->multisession != $params['multisession']) {

            $db = new DB('Franquicias');

            $sp = utf8_decode("CALL SP_Sys_User_editUser({$this->idsys_user},'{$params['username']}','{$params['first_name']}','{$params['last_name']}','{$params['email']}',{$params['multisession']});");

            $res_data = $db->queryRow($sp);

            if (PEAR::isError($res_data)) {
                throw new SysException('Ocurrio un error en la base de datos', $res_data->getDebugInfo());
            }

            if (isset($res_data['_error']) && $res_data['_error']) {
                throw new SysException($res_data['_error_msg']);
            }

            $db->disconnect();
        }

        // nuevo password
        if(!empty($params['new_pswd'])) {
            $this->changePswd($params['new_pswd']);
        }
        
        return true;
    }
    
    /**
     * Actualiza el password en base de datos
     * @param string $new_pswd
     * @return boolean
     * @throws SysException
     */
    private function changePswd($new_pswd) {
        if (empty($new_pswd)) {
            throw new SysException('Error: nuevo password vacio');
        }
        
        require_once dirname(__FILE__).'/../PasswordHash.php';
        
        $db = new DB('Franquicias');

        //encriptar contraseña
        $hasher = new PasswordHash(8, false);

        $pswd_hashed = $hasher->HashPassword($new_pswd);

        if (strlen($pswd_hashed) < 20) {
            throw new SysException('Ocurrió un error al crear el usuario');
        }

        $sp = "CALL SP_Sys_User_changeUserPassword({$this->idsys_user},'{$pswd_hashed}');";
        
        $res_pswd = $db->queryRow($sp);

        if (PEAR::isError($res_pswd)) {
            throw new SysException('Ocurrio un error en la base de datos', $res_pswd->getDebugInfo());
        }

        if (isset($res_pswd['_error']) && $res_pswd['_error']) {
            throw new SysException($res_pswd['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }

    /**
     * Función para que un usuario cambie su propia contraseña
     * @param array $params
     * @return boolean
     * @throws SysException
     */
    public function changeMyPswd($params) {
        if (empty($params) || !is_array($params)) {
            throw new SysException('Error: parametros vacios');
        }
        
        require_once dirname(__FILE__).'/../PasswordHash.php';
        require_once dirname(__FILE__).'/../form.validator.inc.php';

        $form_fields = array(
            'current_pswd' => array('required'=>true,'conditions'=>array('alphanum','length[5,20]')),
            'new_pswd'     => array('required'=>true,'conditions'=>array('alphanum','length[5,20]')),
            'confirm_pswd' => array('required'=>true,'conditions'=>array('confirm[new_pswd]'))
        );
        
        $form = new FormValidator($form_fields, 'waform');

        $valid = $form->validate($params);

        if (!$valid) {
            throw new SysException(implode('<br/>', $form->getErrors()));
        }
        
        //valida usuario esté registrado en sistema y regresa el hash del pswd
        $check_login = User::checkLogin($this->username);

        $hasher = new PasswordHash(8, false);

        //valida que el password sea correcto
        if ($hasher->CheckPassword($params['current_pswd'], $check_login['pswd'])) {
            $this->changePswd($params['new_pswd']);
        } else {
            throw new SysException('Contraseña Actual incorrecta');
        }
        
        return true;
    }

    /**
     * Coloca estatus de borrado al usuario registrado en sistema
     * @return boolean
     * @throws SysException
     */
    public function delete() {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_editUserStatus({$this->idsys_user},3);";
        
        $res_pswd = $db->queryRow($sp);

        if (PEAR::isError($res_pswd)) {
            throw new SysException('Ocurrio un error en la base de datos', $res_pswd->getDebugInfo());
        }

        if (isset($res_pswd['_error']) && $res_pswd['_error']) {
            throw new SysException($res_pswd['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }
    
        /**
     * Cambia estatus al usuario registrado en sistema
     * @return boolean
     * @throws SysException
     */
    public function change_status($idsys_status) {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_changeUserStatus({$this->idsys_user},$idsys_status);";
        
        $res_pswd = $db->queryRow($sp);

        if (PEAR::isError($res_pswd)) {
            throw new SysException('Ocurrio un error en la base de datos', $res_pswd->getDebugInfo());
        }

        if (isset($res_pswd['_error']) && $res_pswd['_error']) {
            throw new SysException($res_pswd['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }
        
    /**
     * Obtiene los roles del usuario
     * @return array
     * @throws SysException
     */
    public function getRoles() {
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_getRoles($this->idsys_user);";
        
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
    
    /**
     * Asigna un nuevo rol al usuario
     * @param int $idsys_role
     * @return boolean
     * @throws SysException
     */
    public function assignRole($idsys_role) {
        
        if (empty($idsys_role) || !filter_var($idsys_role, FILTER_VALIDATE_INT)) {
            throw new SysException('id de role vacio o formato de id de rol no valido');
        }
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_assignRole({$this->idsys_user},$idsys_role);";
        
        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }
    
    /**
     * Desasigna un rol al usuario
     * @param int $idsys_role
     * @return boolean
     * @throws SysException
     */
    public function unassignRole($idsys_role) {
        
        if (empty($idsys_role) || !filter_var($idsys_role, FILTER_VALIDATE_INT)) {
            throw new SysException('id de role vacio o formato de id de rol no valido');
        }
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_unassignRole({$this->idsys_user},$idsys_role);";
        
        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return true;
    }
    
    /**
     * Verifica si un role pertenece al usuario
     * @param int $idsys_role
     * @return boolean
     * @throws SysException
     */
    public function hasRole($idsys_role) {
        if (empty($idsys_role) || !filter_var($idsys_role, FILTER_VALIDATE_INT)) {
            throw new SysException('id de role vacio o formato de id de rol no valido');
        }
        
        $db = new DB('Franquicias');

        $sp = "CALL SP_Sys_User_hasRole({$this->idsys_user},$idsys_role);";
        
        $res = $db->queryRow($sp);

        if (PEAR::isError($res)) {
            throw new SysException('Ocurrio un error en la base de datos', $res->getDebugInfo());
        }

        if (isset($res['_error']) && $res['_error']) {
            throw new SysException($res['_error_msg']);
        }

        $db->disconnect();
        
        return $res['has_role'];
    }
}

?>
