<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Get and store the product ID
    $prodID = htmlspecialchars(trim($_POST['ID']));

    //Instantiate product object to retrieve info
    $product = new Product();

    $product->querySingleItem($prodID);//Ready all our information
    //Get our base information from products table
    $productInfo = $product->quickView($prodID);

    //Get information from prodopt table, Specifically opt_Group and opt_Name
    //We're really just going to implement this like we did on the shop-single.php page
    $optionGroups = $product->getProductOptionGroups();

    //Declare options variable to store array of possible options
    $options;
    //Process the option groups and pull back all possible options
    $i = 0;
    foreach($optionGroups as $group)
    {
        $options[$i] = $product->getProductOptions($group['opt_Group']);
        $i++;
    }

    //Let's get everything else and wrap it up into our assoc array
    $i = 0;

    foreach($productInfo as $key => $value)
    {
        //Grab product ID to send off for image collection
        $imageID = $productInfo[$i]['ID'];
        $avgScore = $productInfo[$i]['avgScore'];

        $productInfo[$i]['image'] = Image::getImageAjax($imageID);
        $productInfo[$i]['avgScore'] = Review::staticAvgRating($avgScore);
        //Load up the option groups
        $productInfo[$i]['opt_Group'] = $optionGroups;
        $productInfo[$i]['opt_Value'] = $options;
    }
    echo json_encode($productInfo);

?>