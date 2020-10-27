<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store posted info into variables
    $prodID = htmlspecialchars(trim($_POST['ID']));
    $quantity = htmlspecialchars(trim($_POST['quantity']));
    $cartID = $_COOKIE['cartID'];

    
    //Check qty from products before moving forward
    $checkNumberItems = new Product();
    $qtyResult = $checkNumberItems->getQuantity($prodID);
    $tableQty = $qtyResult[0]['pro_Qty'];

    //If we can complete the transaction,
    if($tableQty >= $quantity)
    {
        //Instantiate new cart object
        $addTo = new Cart();
        //Send data to insert function and return result
        $result = $addTo->addToCart($cartID, $prodID, $quantity);

        //Quantity to update table with:
        $updateQty = $tableQty - $quantity;
        //Reduce product qty in product table
        $checkNumberItems->removeQty($prodID, $updateQty);
        // build json for our results
        echo json_encode(array("cartQty"=>$result[0], "message"=>"success"));
        
    }
    else
    {
        echo json_encode(array('message' => "lowQty"));
    }

?>