<?

//Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Catch the stringified JSON
    $data = json_decode($_POST['data'], true);

    $min = $data['min'];
    $max = $data['max'];
    $value = $data['value'];
    $type = $data['type'];

    //convert back to appropriate datatypes for db query
    $min = (float)$min;
    $max = (float)$max;
    if(!empty($value))
    {
        $value = (int)$value;
    }
    $type = (string)$type;

    //$min = 2100.00;
    //$max = 7788.00;
    //$value = 100;
    //$type = 'main';

    //Instantiate a filtering object
    $filterPrice = new Filter();
    //store query
    $results = $filterPrice->getProductsInRange($min, $max, $value, $type);

    $customJSON = array();
    $i = 0;

    //echo $results[0]['ID'];
    //Unwrap our results array and customize it w/ Image and Rating info 
    foreach($results as $key => $value)
    {
        //Grab product ID to send off for image collection
        $imageID = $results[$i]['ID'];
        $avgScore = $results[$i]['avgScore'];
        //echo $results[$i]['avgScore'];
        //Append assoc array with image path
        $results[$i]['image'] = Image::getImageAjax($imageID);
        //Append assoc array with correct avgScore info
        $results[$i]['avgScore'] = Review::staticAvgRating($avgScore);

        //Next array item
        ++$i;
    }

    echo json_encode($results);

    
?>