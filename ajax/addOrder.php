<?
session_start();//For grabbing user ID

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Store all of our required information to add the address to db
    //Shipping info
    $shipStreet1 = htmlspecialchars(trim($_POST['shipStreet1']));
    $shipStreet2 = htmlspecialchars(trim($_POST['shipStreet2']));
    $shipCity = htmlspecialchars(trim($_POST['shipCity']));
    $shipState = htmlspecialchars(trim($_POST['shipState']));
    $shipZip = htmlspecialchars(trim($_POST['shipZip']));
    //Billing info
    $billStreet1 = htmlspecialchars(trim($_POST['billStreet1']));
    $billStreet2 = htmlspecialchars(trim($_POST['billStreet2']));
    $billCity = htmlspecialchars(trim($_POST['billCity']));
    $billState = htmlspecialchars(trim($_POST['billState']));
    $billZip = htmlspecialchars(trim($_POST['billZip']));

    //Card information
    $

    //Create a new address object so we check OR add address
    $address = new Address();

    $response = [];




?>