<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Call static function to get our image
    $image = Image::getImage($_REQUEST['data']);

    echo json_encode($image);


?>