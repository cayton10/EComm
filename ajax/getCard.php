<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store address ID variable
    $cardID = htmlspecialchars(trim($_POST['card']));

    //Create a new customer object
    $customer = new Customer();

    $prevCard = $customer->getPrevCard($cardID);


    echo json_encode($prevCard);

?>