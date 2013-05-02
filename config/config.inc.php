<?php

define('DB_HOST','DB_HOST');
define('DB_NAME','DB_NAME');
define('DB_USER','DB_USER');
define('DB_PSWD','DB_PSWD');
define('DB_DRIVER','DB_DRIVER');

/** DB CONNECTIONS */
$dsn = array(
    'Franquicias' => array(
        DB_HOST    => 'localhost',
        DB_NAME    => 'coria',
        DB_USER    => 'root',
        DB_PSWD    => 'mysql04Pswd',
        DB_DRIVER  => 'mysqli'
    )
);

/** ARRAY FOR DB CONNECTIONS */
define('DSN',serialize($dsn));

/** SYSTEM URL */
define('BASE_URL','http://dipbd.gil/');

/** BITS */
define('SERVER_64bits',false);

/** UNDER MAINTENANCE */
define('UNDER_MAINTENANCE',false);

/** SYS_ERROR LOG PATH */
define('SYS_ERROR_LOG_PATH',dirname(__FILE__).'/../logs/');

/** SYS_DEBUG */
define('SYS_DEBUG',TRUE);


?>
