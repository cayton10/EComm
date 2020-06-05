<? 
    session_start();
    //CREATE DB CONNECTION 
    define('DB_HOST', 'localhost');
    //define('DB_USER', 'root');
    //define('DB_PW', 'root');
    define('DB_USER', 'CIT485S20');
    define('DB_PW', 'cit485s20data1!');
    define('DB_NAME', 'CIT485S20');
    //Create variable 'db' with mysqli class using appropriate DB parameters
    @$db = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
?>