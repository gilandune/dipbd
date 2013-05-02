<?php

require_once dirname(__FILE__).'/../../config/config.inc.php';

class DB {
    private $db;
    
    public function __construct($conn) {
        
        require_once 'MDB2.php';

        $dsn_data = unserialize(DSN);

        if (!array_key_exists($conn, $dsn_data)) {
            throw new SysException('Error al conectarse a la base de datos',array(
                'conn' => "Conexión no válida: $conn"
            ));
        }
        
        $dsn = array(
            'phptype'  => $dsn_data[$conn][DB_DRIVER],
            'username' => $dsn_data[$conn][DB_USER],
            'password' => $dsn_data[$conn][DB_PSWD],
            'hostspec' => $dsn_data[$conn][DB_HOST],
            'database' => $dsn_data[$conn][DB_NAME],
        );

        $options = array(
            'debug'       => 2,
            'portability' => MDB2_PORTABILITY_ALL,
            'multi_query' => false
        );

        $this->db =& MDB2::factory($dsn, $options);

        $this->db->setFetchMode(MDB2_FETCHMODE_ASSOC);
        
//        $this->db->loadModule('Datatype');
//        $this->db->loadModule('Manager');
//        $this->db->loadModule('Function');
        

        if (PEAR::isError($this->db)) {
            throw new SysException('Error al conectarse a la base de datos', array(
                'message' => $this->db->getMessage(),
                'extra'   => $this->db->getDebugInfo()
            ));
        }
    }
    
    public function __call($function, $param) {
        $param = implode(',', $param);
        return $this->db->{"$function"}($param);
    }
}
?>
