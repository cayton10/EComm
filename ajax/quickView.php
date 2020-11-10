<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Get and store the product ID
    $prodID = htmlspecialchars(trim($_POST['ID']));

    //Instantiate product object to retrieve info
    $product = new Product();


    $productInfo = $product->quickView($prodID);

    //Let's get everything else and wrap it up into our assoc array
    $i = 0;

    foreach($productInfo as $key => $value)
    {
        //Grab product ID to send off for image collection
        $imageID = $results[$i]['ID'];
        $avgScore = $results[$i]['avgScore'];

        $productInfo[$i]['image'] = Image::getImage($imageID);
        $productInfo[$i]['avgScore'] = Review::staticAvgRating($avgScore);
    }
    echo json_encode($productInfo);

?>