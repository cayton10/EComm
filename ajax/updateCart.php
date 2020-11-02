<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Grab posted values for DB updating
    $data = json_decode($_POST['data']);
    //$data = array($stdData);
    $cartID = $_COOKIE['cartID'];

    //Instantiate a cart object
    $cart = new Cart();

    //Remove the cart completely
    $cart->clearCart($cartID);


    //Reset the cart with the items we're updating w/
    foreach($data as $item)
    {
        //Set the values we're going to pass from our std object
        $id = $item->id;
        $qty = $item->qty;
        $cart->addToCart($cartID, $id, $qty);
    }

    echo json_encode($data);

?>