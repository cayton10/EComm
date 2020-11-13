<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Grab posted values for DB updating
    $data = json_decode($_POST['data']);
    
    //$data = array($stdData);
    $cartID = $_COOKIE['cartID'];

    //Instantiate a cart object
    $cart = new Cart();

    //Clear items from cart that aren't read?
    $cart->clearCart($cartID);
    

    //Reset the cart with the items we're updating w/
    $cart->updateCart($cartID, $data);
    

    echo json_encode($data);

?>