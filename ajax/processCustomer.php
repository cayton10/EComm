<?
session_start();//For grabbing user ID

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store all of our required information to add the address to db
    //Billing info
    $billStreet1 = htmlspecialchars(trim($_POST['billStreet1']));
    $billStreet2 = htmlspecialchars(trim($_POST['billStreet2']));
    $billCity = htmlspecialchars(trim($_POST['billCity']));
    $billState = htmlspecialchars(trim($_POST['billState']));
    $billZip = htmlspecialchars(trim($_POST['billZip']));
    //Shipping info
    $shipStreet1 = htmlspecialchars(trim($_POST['shipStreet1']));
    $shipStreet2 = htmlspecialchars(trim($_POST['shipStreet2']));
    $shipCity = htmlspecialchars(trim($_POST['shipCity']));
    $shipState = htmlspecialchars(trim($_POST['shipState']));
    $shipZip = htmlspecialchars(trim($_POST['shipZip']));
    //Card info
    $cardNum = htmlspecialchars(trim($_POST['cardNum']));
    $cardName = htmlspecialchars(trim($_POST['cardName']));
    $expMonth = htmlspecialchars(trim($_POST['expMonth']));
    $expYear = htmlspecialchars(trim($_POST['expYear']));
    $secCode = htmlspecialchars(trim($_POST['secCode']));

    $cusID = $_SESSION['user'];
    



    if($billStreet2 === '')
    {
        unset($billStreet2);

        $billStreet2 = NULL;
    }
    //Create a new address object so we check OR add address
    $address = new Address();

    //Check if billing address exists, if it doesn't, add it
    $billingAdd = $address->checkAddress($billStreet1, $billStreet2, $billCity, $billState, $billZip, $cusID);



    echo json_encode($billingAdd);


?>