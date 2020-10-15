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

    echo json_encode($results);

    
?>