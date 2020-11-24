<? 
    //CREATE DB CONNECTION 
    define('DB_HOST', 'localhost');
    //define('DB_HOST', 'cit.marshall.edu' ); // set database host
    define('DB_USER', 'CIT410F20');
    define('DB_PASS', 'This1sAS3cr3t!');
    define('DB_NAME', 'cit410f20');
    define( 'SEND_ERRORS_TO', 'cayton10@marshall.edu' ); //set email notification email address
    define( 'DISPLAY_DEBUG', true ); //display db errors?
    define( 'PATH_TO_IMAGES', '../../products/');//I don't like having this hard coded. Ask Brian how to fix
    define( 'PATH_TO_CLASSES',  $_SERVER['DOCUMENT_ROOT'] . '/CIT410/cayton10/EComm/classes/');
    // PHP 7 way to do autoload
    spl_autoload_register(function ($class) {
    include PATH_TO_CLASSES . $class . '_class.php';
});

    session_start();
?>
