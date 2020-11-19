<?session_start();
//Include config file
require_once(__DIR__ . "/../config/config.php");


//Declare session variable to store user id
$_SESSION['user'];

//Grab posted data
$email = htmlspecialchars(trim($_POST['email']));
$pw = htmlspecialchars(trim($_POST['pass']));

//Encrypt password
$pw = md5($pw);

$customer = new Customer();

$valid = $customer->checkUserAndPass($email, $pw);

//Declare an array to return JSON response
$response = [];

$uID = $valid[0]['id'];
$uID = md5($uID);

//Control flow for returned $valid value
if($valid != 0)
{
    $uID = $valid[0]['id'];
    $uID = md5($uID);

    $response['success'] = true;
    $response['message'] = "Valid email and password!";
    //Encrypt the customer ID for session var use
    $_SESSION['user'] = $uID;
}
else
{
    $response['success'] = false;
    $response['message'] = "Invalid email and / or password. Try again.";
}

echo json_encode($response);


?>