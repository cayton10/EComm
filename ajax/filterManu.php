<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store posted info to variables
    $manuName = $_POST['name'];
    $maxPrice = $_POST['maxPrice'];
    $minPrice = $_POST['minPrice'];
    $type = $_POST['type'];
    $value = $_POST['value'];
    $sortType = $_POST['sortType'];
    $sort = $_POST['sort'];


    //Instantiate object to call filter class method
    $filter = new Filter();
    
    $products;


    //Fire appropriate query to store results for all manufacturers
    $products = $filter->getProductsInRange($minPrice, $maxPrice, $value, $type, $manuName, $sortType, $sort);


    $i = 0;

    //echo $results[0]['ID'];
    //Unwrap our results array and customize it w/ Image and Rating info 
    foreach($products as $key => $value)
    {
        //Grab product ID to send off for image collection
        $imageID = $products[$i]['ID'];
        $avgScore = $products[$i]['avgScore'];
        //echo $results[$i]['avgScore'];
        //Append assoc array with image path
        $products[$i]['image'] = Image::getImageAjax($imageID);
        //Append assoc array with correct avgScore info
        $products[$i]['avgScore'] = Review::staticAvgRating($avgScore);

        //Next array item
        ++$i;
    }

    echo json_encode($products);

?>