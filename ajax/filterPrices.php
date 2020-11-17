<?

//Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Catch the stringified JSON
    $data = json_decode($_POST['data'], true);

    $min = $data['min'];
    $max = $data['max'];
    $value = $data['value'];
    $type = $data['type'];
    $manu = $data['manu'];
    $sortType = $data['sortType'];
    $sort = $data['sort'];

    //convert back to appropriate datatypes for db query
    $min = (float)$min;
    $max = (float)$max;
    if(!empty($value))
    {
        $value = (int)$value;
    }
    $type = (string)$type;
    $manu = (string)$manu;
    //Reassign values of sort type so we can query with a little more ease
    if(!empty($sortType))
    {
        if($sortType == 'manu')
        {
            $sortType = 'pro_Manufacturer';
        }
        else
            $sortType = 'pro_Price';
    }



    //Instantiate a filtering object
    $filterPrice = new Filter();
    //store query
    $results = $filterPrice->getProductsInRange($min, $max, $value, $type, $manu, $sortType, $sort);

    $i = 0;
    
    //Unwrap our results array and customize it w/ Image and Rating info 
    foreach($results as $key => $value)
    {
        //Grab product ID to send off for image collection
        $imageID = $results[$i]['ID'];
        $avgScore = $results[$i]['avgScore'];

        //Append assoc array with image path
        $results[$i]['image'] = Image::getImageAjax($imageID);
        //Append assoc array with correct avgScore info
        $results[$i]['avgScore'] = Review::staticAvgRating($avgScore);

        //Next array item
        ++$i;
    }
    echo json_encode($results);

    
?>