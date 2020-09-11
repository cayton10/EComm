<? 
    session_start();
    //CREATE DB CONNECTION 
    define('DB_HOST', 'localhost');
    //define( 'DB_HOST', 'cit.marshall.edu' ); // set database host
    //define('DB_USER', 'root');
    define('DB_USER', 'CIT410F20');
    //define('DB_PW', 'root');
    define('DB_PW', 'This1sAS3cr3t!');
    define('DB_NAME', 'cit410f20');
    define( 'SEND_ERRORS_TO', 'YOUREMAIL@marshall.edu' ); //set email notification email address
    define( 'DISPLAY_DEBUG', true ); //display db errors?
    define( 'PATH_TO_CLASSES', "../classes/" );


    // PHP 7 way to do autoload
    spl_autoload_register(function ($class) {
    include PATH_TO_CLASSES . $class . '_class.php';
});
?>