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
    $cardExp = htmlspecialchars(trim($_POST['cardExp']));
    $secCode = htmlspecialchars(trim($_POST['secCode']));
    $cardActive = 'Y';

    //Grab user ID so we can send for required info
    $cusID = $_SESSION['user'];
    



    if($billStreet2 === '')
    {
        unset($billStreet2);
        $billStreet2 = NULL;//This is driving me insane. Using NULL doesn't work on DB insert
    }

    if($shipStreet2 === '')
    {
        unset($shipStreet2);
        $shipStreet2 = NULL;
    }

    //Create a new address object so we check OR add address
    $address = new Address();

    //Check if billing address exists, if it doesn't, add it
    $billingAdd = $address->checkAddress($billStreet1, $billStreet2, $billCity, $billState, $billZip, $cusID);


    foreach($billingAdd as $addrCode)
    {
        $billingAdd = $addrCode['add_ID'];
    }

    //Set the billing address id in session variable
    $_SESSION['billingAdd'] = $billingAdd;

    //Do the same with shipping address
    $shippingAdd = $address->checkAddress($shipStreet1, $shipStreet2, $shipCity, $shipState, $shipZip, $cusID);

    foreach($shippingAdd as $addrCode)
    {
        $shippingAdd = $addrCode['add_ID'];
    }

    //Set the shipping address id in session variable
    $_SESSION['shippingAdd'] = $shippingAdd;

    //Create a new Creditcard object so we check OR add card
    $card = new Creditcard();

    //Check if card exists, if it doesn't, add it
    $creditCard = $card->checkCard($cardNum, $cardName, $cardExp, $secCode, $cardActive, $billingAdd, $cusID);

    foreach($creditCard as $cardCode)
    {
        $creditCard = $cardCode['car_ID'];
    }

    //Set card ID in session variable for adding to order table
    $_SESSION['cardID'] = $creditCard;
    


    echo json_encode($creditCard);


?>