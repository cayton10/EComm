<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store posted info into variables
    $prodID = htmlspecialchars(trim($_POST['ID']));
    $quantity = htmlspecialchars(trim($_POST['quantity']));
    $options = json_decode($_POST['option']);
    $cartID = $_COOKIE['cartID'];

 

    //Check qty from products before moving forward
    $checkNumberItems = new Product();
    $qtyResult = $checkNumberItems->getQuantity($prodID);
    $tableQty = $qtyResult[0]['pro_Qty'];

    //If we can complete the transaction,
    if($tableQty >= $quantity && empty($options))
    {
        //Instantiate new cart object
        $addTo = new Cart();
        //Send data to insert function and return result
        $result = $addTo->addToCart($cartID, $prodID, $quantity, $options);

        // build json for our results
        echo json_encode(array("cartQty"=>$result[0], "message"=>"success"));
        
    }
    else if(!empty($options))
    {
        $addTo = new Cart();

        $result;
        
        //Send all our posted info to addToCart class method
        $result = $addTo->addToCart($cartID, $prodID, $quantity, $options);

        if($result > 0)
        {
            echo json_encode(array('cartQty'=>$result[0], 'message'=>'success'));
        }
        else
        {
            echo json_encode(array('message' =>'no bueno'));
        }

        
        

    }
    else
    {
        echo json_encode(array('message' => "lowQty"));
    }

?>