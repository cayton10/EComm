<?
    //Include config file
    require_once(__DIR__ . "/../config/config.php");
    //Information passed from the review container is sent here for proecssing

//TODO: REPLACE THIS ISSET WITH SESSION WHEN WE GET TO SESSIONS AND ADDING CUSTOMERS
    if(isset($_POST['prodID']))
    {
        //Store all of the posted data into variables for ease
        $prodID = htmlspecialchars(trim($_POST['prodID']));
        $prodID = (int)$prodID;
        $rating = htmlspecialchars(trim($_POST['rating']));
        $rating = (int)$rating;
        $reviewDetail = htmlspecialchars(trim($_POST['reviewDetail']));
        //Hard code customer ID
        $cusID = 1;
        //Create object of review so can call the method to insert to DB
        $reviewObj = new Review($prodID);
        
        $affectedRows = $reviewObj->insertReview($rating, $reviewDetail, $cusID);
    }

    // return a JSON encoded string containing affected rows
    header('Content-Type: application/json');
    echo json_encode($affectedRows);

?>