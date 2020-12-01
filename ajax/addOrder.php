<?
session_start();//For grabbing user ID

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Include info required for grabielbull's ups-api wrapper
    require_once(__DIR__ . "/../classes/vendor/autoload.php");

    //Store our shipping cost grabbed from DOM
    $shipCost = htmlspecialchars(trim($_POST['shippingCost']));

    //Declare our response JSON array
    $response = [];

    //Load all of our required information from session variables
    //Get billing address
    $billing = $_SESSION['billingAdd'];
    //Shipping address
    $shipping = $_SESSION['shippingAdd'];
    //Credit Card ID
    $card = $_SESSION['cardID'];
    //Customer ID
    $cusID = $_SESSION['user'];
    //Cart ID
    $cartID = session_id();

    //Started looking into setting shipment reference numbers to get tracking info, but running out of time
    //Setting random tracking using uniqid
    $track = uniqid();
    
    $newOrder = new Order();

    $orderID = $newOrder->addOrder($billing, $shipping, $card, $cusID, $shipCost, $track);

    //Create instance of cart class to grab cart details
    $cart = new Cart();
    $cartInfo = $cart->getCartDetail($cartID);
    
    //Now we need to process information into orderdetail table
    $orderDetail = $newOrder->addOrderDetail($orderID, $cartInfo);

    if($orderDetail == 0)
    {
        $response['success'] = false;
        $response['message'] = "Order details cannot be added at this time.";
        echo json_encode($response);
    }

    //Process information into order detail options table
    $orderDetailOpts = $newOrder->addOrderDetailOpts($orderID, $cartInfo);

    if($orderDetailOpts == 0)
    {
        $response['success'] = false;
        $response['message'] = "Order options cannot be added at this time.";
        echo json_encode($response);
    }

    print_r($orderDetail);

    echo json_encode($orderID);



?>