<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store address ID variable
    $addID = $_POST['addID'];

    //Create a new customer object
    $customer = new Customer();

    $prevAdd = $customer->getPrevAddress($addID);


    echo json_encode($prevAdd);


?>