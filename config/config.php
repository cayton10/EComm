<? 
    session_start();
    //CREATE DB CONNECTION 
    define('DB_HOST', 'localhost');
    //define('DB_USER', 'root');
    //define('DB_PW', 'root');
    define('DB_USER', 'CIT410F20');
    define('DB_PW', 'This1sAS3cr3t!');
    define('DB_NAME', 'cit410f20');
    //Create variable 'db' with mysqli class using appropriate DB parameters
    @$db = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);


?>