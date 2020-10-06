<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");
    //Information passed from the search box in header.php is sent here for DB processing
    //using the static function within the 'Search_class.php' file
    $searchObj = new Search();
    $searchResults = $searchObj->searchProdName($_REQUEST['search']);

    // return a JSON encoded string that has the product details within it
    echo json_encode($searchResults);
?>